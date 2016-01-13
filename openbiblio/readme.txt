block_openbiblio

Purpose
-------
This block reads the OpenBiblio database and pulls out which books an in 
Moodle logged-in user has borrowed and shows them with their due by date.
Moodle needs to be able to read the OpenBiblio database and needs to know
how to identify someone in that database. This data needs to be put in the block 
settings page in the blocks admin menu.

Installation
------------
* Install this block as any other block by putting the entire openbiblio block 
folder in your moodle/blocks folder and visit the admin page.
* You'll be prompted with the settings page.
* Add the block to a page.

Changes
-------
* 20130928:
added function has_config() {return true;} to make installation work on 2.4 and higher. Thanks to Linda Vanderbaan.

* 20151230:
added $plugin->maturity
added $plugin->component to make compatible for Moodle 3.0
moved code to github

* 2015011301:
mysql_connect to mysqli_connect
missing folder from move

Koen Roggemans
koen@roggemans.net
