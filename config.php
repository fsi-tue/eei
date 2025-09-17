<?php

error_reporting(E_ALL);
date_default_timezone_set("Europe/Berlin");

$CSV_OPTIONS = array(
	'separator' => ',',
	'enclosure' => '"',
	'escape' => '\\',
);
$FILE_REVISION = "?v=" . file_get_contents(__DIR__ . "/.git/refs/heads/master", FALSE, NULL, 0, 40);

# General Information
$CONFIG_CONTACT = 'fsi@fsi.uni-tuebingen.de';
$CONFIG_TERM = 'Sommersemester 25'; # example: 'Wintersemester 2023/24' or 'Sommersemester 2024'
$CONFIG_TERM_SHORT = 'SS25'; # example: 'WS23_24' or 'SS24'
$CONFIG_THEME = 'fsi-fsk'; # possible values: 'fsi-fsk' or 'fsi-only'
// see https://www.php.net/manual/en/language.constants.predefined.php
$fp = realpath(__DIR__ . "/../eei-registration/") . "/"; #File Prefix
