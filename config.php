<?php

error_reporting(E_ALL);
date_default_timezone_set("Europe/Berlin");

$FILE_REVISION = "?v=" . file_get_contents(__DIR__ . "/.git/refs/heads/master", false, NULL, 0, 40);

# General Information
$CONFIG_CONTACT = 'fsi@fsi.uni-tuebingen.de';
$CONFIG_TERM = 'WS 24/25'; # example: WS 19/20 or SS 20
// see https://www.php.net/manual/en/language.constants.predefined.php
$fp = realpath(__DIR__ . "/../eei-registration/") . "/"; #File Prefix
