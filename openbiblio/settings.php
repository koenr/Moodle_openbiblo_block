<?php
defined('MOODLE_INTERNAL') || die;
if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configtext('openbiblio/hostname', get_string('hostname', 'block_openbiblio'), get_string('deschostname','block_openbiblio'),"localhost",PARAM_TEXT));
    $settings->add(new admin_setting_configtext('openbiblio/database', get_string('database_name', 'block_openbiblio'), get_string('descdatabase_name', 'block_openbiblio'),"openbiblio",PARAM_TEXT));
    $settings->add(new admin_setting_configtext('openbiblio/database_username', get_string('database_username', 'block_openbiblio'), get_string('descdatabase_username', 'block_openbiblio'),"openbiblio",PARAM_TEXT));
    $settings->add(new admin_setting_configtext('openbiblio/passwd',get_string('database_password', 'block_openbiblio'), get_string('descdatabase_password', 'block_openbiblio'),"password",PARAM_TEXT));
    $settings->add(new admin_setting_configtext('openbiblio/libraryid', get_string('libraryid', 'block_openbiblio'), get_string('desclibraryid', 'block_openbiblio'),"idnumber",PARAM_TEXT));
    $settings->add(new admin_setting_configtext('openbiblio/opac_url', get_string('opac_url', 'block_openbiblio'), get_string('descopac_url','block_openbiblio'),"http://your.library.edu/openbiblio/opac",PARAM_TEXT));
}
?>
