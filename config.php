<?php

    error_reporting(E_ERROR);
    date_default_timezone_set("Europe/Berlin");

    $FILE_REVISION = "?v=" . file_get_contents(__DIR__ . "/.git/refs/heads/master", NULL, NULL, 0, 40);

    #General Informations
    $CONFIG_CONTACT = 'fsi@fsi.uni-tuebingen.de';
    $CONFIG_TERM = 'WS 22/23'; # example: WS 19/20 or SS 20
    // see https://www.php.net/manual/en/language.constants.predefined.php
    $fp = __DIR__ . "/../eei-registration/"; #File Prefix
    
    $SENDER_EMAIL = "fsi@fsi.uni-tuebingen.de";
    $SENDER_NAME  = "EEI - Fachschaft Informatik"
?>
