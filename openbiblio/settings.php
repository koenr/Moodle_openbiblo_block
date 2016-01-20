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
defined('MOODLE_INTERNAL') || die;
if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configtext('openbiblio/hostname', get_string('hostname', 'block_openbiblio'), get_string('deschostname', 'block_openbiblio'), "localhost", PARAM_TEXT));
    $settings->add(new admin_setting_configtext('openbiblio/database', get_string('database_name', 'block_openbiblio'), get_string('descdatabase_name', 'block_openbiblio'), "openbiblio", PARAM_TEXT));
    $settings->add(new admin_setting_configtext('openbiblio/database_username', get_string('database_username', 'block_openbiblio'), get_string('descdatabase_username', 'block_openbiblio'), "openbiblio", PARAM_TEXT));
    $settings->add(new admin_setting_configtext('openbiblio/passwd', get_string('database_password', 'block_openbiblio'), get_string('descdatabase_password', 'block_openbiblio'), "password", PARAM_TEXT));
    $settings->add(new admin_setting_configtext('openbiblio/libraryid', get_string('libraryid', 'block_openbiblio'), get_string('desclibraryid', 'block_openbiblio'), "idnumber", PARAM_TEXT));
    $settings->add(new admin_setting_configtext('openbiblio/opac_url', get_string('opac_url', 'block_openbiblio'), get_string('descopac_url', 'block_openbiblio'), "http://your.library.edu/openbiblio/opac", PARAM_TEXT));
}

