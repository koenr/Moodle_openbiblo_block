<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/******************************************************************************
* First read the readme file for installation and known problems
 *
 * @author Koen Roggemans
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
******************************************************************************/

class block_openbiblio extends block_base {
    public function init() {
          $this->title   = get_string('openbiblio', 'block_openbiblio');
          $this->content_type = BLOCK_TYPE_TEXT;
          $this->version = 2016012002;
    }

    public function has_config() {
        return true;
    }

    public function get_content() {
        global $USER;
        if ($this->content !== null) {
            return $this->content;
        }
        $this->content = new stdClass;
        /* If the user is not logged in, we call it a day*/
        if (!isloggedin()) {
            $this->content->text = get_string('notloggedin', 'block_openbiblio');
            return $this->content;
        }
        /*******************************************************************************
         * Configuration
         ******************************************************************************/

         /* The database settings of your OpenBiblio installation
         **/
         $obibhost      = get_config('openbiblio', 'hostname');
         $obibdatabase  = get_config('openbiblio', 'database');
         $obibusername  = get_config('openbiblio', 'database_username');
         $obibpasswd    = get_config('openbiblio', 'passwd');
         $opacurl       = get_config('openbiblio', 'opac_url');

        /* The moodle userfield that is used for the identification of the
        student in the library. (= what is on the barcode of the students library card).
        We use the idnumber, but it could also be the username or a custum field.
        **/
        $obiblibraryid = get_config('openbiblio', 'libraryid');

        /* If openbiblio_block is not configured, we stop and show a message **/
        if (empty($obiblibraryid)) {
            $this->content->text = get_string('notconfigured', 'block_openbiblio');
            return $this->content;
        }
        $libraryid = $USER->$obiblibraryid;

        /******************************************************************************
        * End of configuration
        ******************************************************************************/

        // OpenBiblio works only with lowercase. Let's make sure we have that.

        $libraryid = strtolower($libraryid);

        // We assume the user doesn't have any books borrowed, unless proven different.
        $numberbooksborrowed = 0;

        // Connect to the openbiblio database and retrieve data about all borrowed books.

        $connection = mysqli_connect($obibhost, $obibusername, $obibpasswd, $obibdatabase);
        // Check connection.
        if (mysqli_connect_errno()) {
            $this->content->text = get_string('databaseerror', 'block_openbiblio').": " . mysqli_connect_error();
            return $this->content;
        }

        // Collect a list of all borrowed books, their title, author, date borrowed, due back and the memberID of who borrowed it.
        $query = <<<SQL
		  select c.bibid, c.copyid, m.mbrid, c.barcode_nmbr copybarcode,
                b.title, b.author, c.status_begin_dt,
                c.due_back_dt, LOWER(m.barcode_nmbr) as memberbarcode
                from biblio b, biblio_copy c, member m
                where b.bibid = c.bibid
                and c.mbrid = m.mbrid
                and c.status_cd = 'out'
SQL;

        $allborrowedbooks = mysqli_query($connection, $query);

        // Check whether this logged-in user has books borrowed and show the information about those books.
        $today = date("Y-m-d");
     	if (isset($this->content->text)) {
            $this->content->text .= "<ol>";
        }
        while ($row = mysqli_fetch_array($allborrowedbooks)) {
            if (($row['memberbarcode']) == $libraryid) {
                $numberbooksborrowed++;
                $this->content->text .= "<li>" . ($row['title']) . " - " . ($row['author']) . "<br /><div class=\"date\"> ";
                $this->content->text .= get_string('duedate', 'block_openbiblio');
                $expire = $row['due_back_dt'];
                if ($today > $expire) {
                    $this->content->text .= " <b>" . ($row['due_back_dt']) . "</b></div></li>";
                } else {
                    $this->content->text .= " " . ($row['due_back_dt']) . "</div></li>";
                }
            }
        }
        mysqli_free_result($allborrowedbooks);
        mysqli_close($connection);

        if (isset($this->content->text)) {
            $this->content->text .= "</ol>";
        }
        // If this user didn't borrow any books, it's nice to know too.
        if ($numberbooksborrowed == 0) {
            $this->content->text = get_string('nobooksborrowed', 'block_openbiblio');
        }
        // Show a link to the OPAC in the footer.
	$opensearch = "";
        $opensearch .= "<script>";
        $opensearch .= "function opensearch(){";
        $opensearch .= "mywindow = window.open ('$opacurl',";
        $opensearch .= "'mywindow','width=780','height=500');";
        $opensearch .= "mywindow.moveTo(0,0);";
        $opensearch .= "}";
        $opensearch .= "</script>";
        $opensearch .= "<a href='javascript:opensearch();'>".get_string('search', 'block_openbiblio')."</a><br />";
        $this->content->footer = $opensearch;

        return $this->content;

    }// Closing curly bracket for the function get_content.
}    // Here's the closing curly bracket for the class definition.
