<?php
/******************************************************************************
* First read the readme file for installation and known problems
* 
* @author Koen Roggemans
* @license http://www.gnu.org/copyleft/gpl.html GNU Public License
******************************************************************************/

class block_openbiblio extends block_base {
  function init() {
    $this->title   = get_string('openbiblio', 'block_openbiblio');
    $this->content_type = BLOCK_TYPE_TEXT;
    $this->version = 2015011301;
    }

  function has_config() {return true;}

  function get_content() {
    global $USER;
    if ($this->content !== NULL) {
      return $this->content;
    }
    $this->content         = new stdClass;
/* If the user is not logged in, we call it a day*/
     if (!isloggedin()){
        $this->content->text = get_string('notloggedin', 'block_openbiblio');
        return $this->content;
    }

/*******************************************************************************
* Configuration
******************************************************************************/

/* The database settings of your OpenBiblio installation 
**/
$obib_host      = get_config('openbiblio', 'hostname');
$obib_database  = get_config('openbiblio', 'database');
$obib_username  = get_config('openbiblio', 'database_username');
$obib_passwd    = get_config('openbiblio', 'passwd');
$opac_url       = get_config('openbiblio', 'opac_url');

/* The moodle userfield that is used for the identification of the 
student in the library. (= what is on the barcode of the students library card).
We use the idnumber, but it could also be the username or a custum field. 
**/
$obib_libraryid = get_config('openbiblio', 'libraryid');

/* If openbiblio_block is not configured, we stop and show a message **/
   if (empty($obib_libraryid)) {
       $this->content->text = get_string('notconfigured', 'block_openbiblio');
       return $this->content;
   }
$libraryid = $USER->$obib_libraryid;

/******************************************************************************
* End of configuration
******************************************************************************/

// OpenBiblio works only with lowercase. Let's make sure we have that

	$libraryid = strtolower($libraryid);

// We assume the user doesn't have any books borrowed, unless proven different
    $number_books_borrowed = 0;

//connect to the openbiblio database and retrieve data about all borrowed books

    if (!($connection = @ mysqli_connect($obib_host, $obib_username, $obib_passwd)))
    {$this->content->text = get_string('databaseerror','block_openbiblio');
    return $this->content;
    }
    mysql_query("SET NAMES 'utf8'");
    mysql_select_db($obib_database,$connection);
    $all_borrowed_books = mysql_query('select c.bibid, c.copyid, m.mbrid, c.barcode_nmbr copybarcode,
		b.title, b.author, c.status_begin_dt,
		c.due_back_dt, LOWER(m.barcode_nmbr) as memberbarcode
	        from biblio b, biblio_copy c, member m
	        where b.bibid = c.bibid
		and c.mbrid = m.mbrid
		and c.status_cd = \'out\' ');
     mysql_close($connection);

// Check whether this logged-in user has books borrowed and show the information about those books        
$this->content->text.= "<ol>";
    while ($row = mysql_fetch_array($all_borrowed_books)) {
        if ($row{'memberbarcode'} == $libraryid) {
            $number_books_borrowed++;
            $this->content->text.= "<li>".$row{'title'}." - ".$row{'author'}."<br /><div class=\"date\"> ".get_string('duedate','block_openbiblio')." ".$row{'due_back_dt'}."</div></li>";
        }
    }
$this->content->text.= "</ol>";
// If this user didn't borrow any books, it's nice to know too
    if ($number_books_borrowed == 0) {
            $this->content->text = get_string('nobooksborrowed','block_openbiblio');
    }
// Show a link to the OPAC in the footer
	$opensearch = "<script>";
	$opensearch .= "function opensearch(){";
	$opensearch .= "mywindow = window.open ('$opac_url',";
	$opensearch .= "'mywindow','width=780','height=500');";
	$opensearch .= "mywindow.moveTo(0,0);";
	$opensearch .= "}";
    $opensearch .= "</script>";
    $opensearch .= "<a href='javascript:opensearch();'>".get_string('search','block_openbiblio')."</a><br />";
    $this->content->footer= $opensearch;

    return $this->content;
  }
}   // Here's the closing curly bracket for the class definition
    // and here's the closing PHP tag from the section above.
?>
