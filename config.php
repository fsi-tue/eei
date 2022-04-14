<?php

    error_reporting(E_ERROR);
    date_default_timezone_set("Europe/Berlin");

    $FILE_REVISION = "?v=" . file_get_contents(__DIR__ . "/.git/refs/heads/master", NULL, NULL, 0, 40);

    #General Informations
    $CONFIG_CONTACT = 'fsi@fsi.uni-tuebingen.de';
    $CONFIG_TERM = 'SS 22'; # example: WS 19/20 or SS 20
    // see https://www.php.net/manual/en/language.constants.predefined.php
    $fp = __DIR__ . "/../eei-registration/"; #File Prefix
    

    $SMTP_HOST = "";
    $SMTP_PORT = 587;
    $STMP_USER = "";
    $SMTP_PASS = "";
    $SMTP_SENDER_EMAIL = "noreply@eei.fsi.uni-tuebingen.de";
    $SMTP_SENDER_NAME  = "EEI - Fachschaft Informatik"


    #Each event is an array in the $events array
    #To add an event copy the dummy and modify the values. Then, append this event to the $events array
    #The order in this array defines the shown order

    #name: Name of the event (string)
    #active: shows if event is actually to happen.
    #icon: Icon of the tile (string), see css/style.css for list of all icons (example: beer)
    #location: Location of the event (string)
    #date: Date and Time of the event (string) in a human readable format
    #online: shows if the event is online
    #cancelled: shows if the evend is cancelled
    #uts: Unix Timestamp of the event (int), or mktime(hour, minute, second, MONTH, DAY, year)  (ATTENTION! American Time Format)
    #     The cookie, that will be set after a registration, will expire at this time +24h
    #     After reaching this timestamp no further registration will be possible  
    #link: Link to the event (string), relativ path to sub-directory
    #path: Relativ path to CSV file (string), only necessary if used 
    #max_participants: Maximum number of participants that are allowed to register before no further registrations will be possible
    #uts_override: Override the default limit for registrations of 74 hours before the event
    #end_of_registration: Custom date for end of registration, set to false otherwise


?>
