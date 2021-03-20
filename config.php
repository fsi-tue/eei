<?php

error_reporting(E_ERROR);
date_default_timezone_set("Europe/Berlin");

$FILE_REVISION = "?v=" . file_get_contents(__DIR__ . "/.git/refs/heads/master", NULL, NULL, 0, 40);

#General Informations
$CONFIG_CONTACT = 'fsi@fsi.uni-tuebingen.de';
$CONFIG_TERM = 'SS 21'; # example: WS 19/20 or SS 20
// see https://www.php.net/manual/en/language.constants.predefined.php
$fp = __DIR__ . "/../eei-registration/"; #File Prefix

/*
#Anfi WE
$WE = ["name" => 'Anfi WE', "icon" => 'house', "active" => false, "location" => 'Irgendwo im Wald', "date" => '14 - 17.02.20', 
       "uts" => mktime('18', '0', '0', '02', '14', '2020'), "link" => 'anfi-we/', "path" => "{$fp}anfi-we.csv"];

#Spieleabend NICHT Akademisch
$SN = ["name" => 'Spieleabend', "icon" => 'dice', "active" => false, "location" => 'Sand 14 - A301', "date" => '30.03.20', 
       "uts" => mktime('18', '0', '0', '03', '30', '2020'), "link" => 'spa/', "path" => "{$fp}anfi-sp.csv"];

#Filmeabend
$FA = ["name" => 'Filmeabend', "icon" => 'film', "active" => false, "location" => 'Sand 14 - A301', "date" => '31.03.20', 
       "uts" => mktime('18', '0', '0', '03', '31', '2020'), "link" => 'film/', "path" => "{$fp}anfi-film.csv"];

#Grillen
$GR = ["name" => 'Grillen', "icon" => 'grill', "active" => false, "location" => 'Wiese - Sand 14', "date" => '02.04.20', 
       "uts" => mktime('16', '0', '0', '04', '02', '2020'), "link" => 'grillen/', "path" => "{$fp}anfi-grill.csv"];

#Wanderung
$WA = ["name" => 'Wanderung', "icon" => 'hiking', "active" => true, "location" => 'vor dem Neckarmüller', "date" => '25.10.20 11 Uhr',
       "uts" => mktime('11', '0', '0', '10', '25', '2020'), "link" => 'wanderung/', "path" => "{$fp}anfi-wanderung.csv"];

#Wanderung 2
$WA1 = ["name" => 'Wanderung #2', "icon" => 'hiking', "active" => true, "location" => 'OMV Tankstelle (WHO)', "date" => '08.11.20 11 Uhr',
        "uts" => mktime('11', '0', '0', '11', '08', '2020'), "link" => 'wanderung2/', "path" => "{$fp}anfi-wanderung-2.csv"];

#Stadtrallye
$RY = ["name" => 'Stadtrallye', "icon" => 'route', "active" => true, "location" => 'wird nach Anmeldung mitgeteilt', "date" => '24.10.20 ~16 Uhr',
       "uts" => mktime('16', '0', '0', '10', '24', '2020'), "link" => 'rallye/', "path" => "{$fp}anfi-rallye.csv"];

#Stadtrallye digital
$RD0 = ["name" => 'Stadtrallye digital', "icon" => 'route', "active" => true, "location" => 'wird nach Anmeldung mitgeteilt', "date" => '01.11.20 15 Uhr',
       "uts" => mktime('15', '0', '0', '11', '01', '2020'), "link" => 'rallye-digital/', "path" => "{$fp}anfi-rallye-digital.csv"];

#Kneipentour
$KT = ["name" => 'Kneipentour', "icon" => 'beer', "active" => true, "location" => 'wird nach Anmeldung mitgeteilt', "date" => 'Fällt aus', 
       "uts" => mktime('19', '0', '0', '10', '30', '2020'), "link" => 'kneipentour/', "path" => "{$fp}anfi-kneipentour.csv"];

#Frühstück
$FR = ["name" => 'Frühstück', "icon" => 'food', "active" => false, "location" => 'Mensa Morgenstelle', "date" => '09.04.20', 
       "uts" => mktime('10', '0', '0', '04', '08', '2020'), "link" => 'fruehstueck/', "path" => "{$fp}anfi-fruestueck.csv"];

#Spieleabend Akademisch
$SA = ["name" => 'Spieleabend akademisch', "icon" => 'cap', "active" => false, "location" => 'Discord', "date" => '06.11.20 19 Uhr', 
       "uts" => mktime('19', '0', '0', '11', '06', '2020'), "link" => 'spa-akad/', "path" => "{$fp}anfi-sp-akad.csv"];
*/

#Spieleabend digital 0
$SD0 = ["name" => 'Spieleabend digital 1', "icon" => 'cap', "active" => true, "location" => 'Discord', "date" => '16.04.2021 19:00 Uhr',
       "uts" => mktime('19', '0', '0', '04', '16', '2021'), "link" => 'spa-digital0/', "path" => "{$fp}anfi-sp-digital-0.csv"];

#Spieleabend digital 1
$SD1 = ["name" => 'Spieleabend digital 2', "icon" => 'cap', "active" => true, "location" => 'Discord', "date" => '24.04.2021 19:00 Uhr',
       "uts" => mktime('19', '0', '0', '04', '24', '2021'), "link" => 'spa-digital1/', "path" => "{$fp}anfi-sp-digital-1.csv"];

#Spieleabend digital 2
$SD2 = ["name" => 'Spieleabend digital 3', "icon" => 'cap', "active" => true, "location" => 'Discord', "date" => '30.04.2021 19:00 Uhr',
       "uts" => mktime('19', '0', '0', '04', '30', '2021'), "link" => 'spa-digital2/', "path" => "{$fp}anfi-sp-digital-2.csv"];

#Stadtrallye digital 0
$RD0 = ["name" => 'Stadtrallye digital 1', "icon" => 'route', "active" => true, "location" => 'Discord', "date" => '21.04.2021 17:00 Uhr',
       "uts" => mktime('17', '0', '0', '04', '21', '2021'), "link" => 'rallye-digital0/', "path" => "{$fp}anfi-rallye-digital-0.csv"];

#Stadtrallye digital 1
$RD1 = ["name" => 'Stadtrallye digital 2', "icon" => 'route', "active" => true, "location" => 'Discord', "date" => '27.04.2021 17:00 Uhr',
       "uts" => mktime('17', '0', '0', '04', '27', '2021'), "link" => 'rallye-digital1/', "path" => "{$fp}anfi-rallye-digital-1.csv"];

$events = ['SD0' => $SD0, 'SD1' => $SD1, /*'SD2' => $SD2,*/
           'RD0' => $RD0, 'RD1' => $RD1 ];  

#Each event is an array in the $events array
#To add an event copy the dummy and modify the values. Then, append this event to the $events array
#The order in this array defines the shown order

$dummy =  ["name" => '', "icon" => '', "active" => true, "location" => 'Test', "date" => 'DD.MM.YY HH', 
"uts" => mktime('17', '59', '0', '12', '31', '2020'), "link" => "subDir/", "path" => "{$fp}/filename.csv"];
#name: Name of the event (string)
#icon: Icon of the tile (string), see css/style.css for list of all icons (example: beer)
#location: Location of the event (string)
#date: Date and Time of the event (string) in a human readable format
#uts: Unix Timestamp of the event (int), or mktime(hour, minute, second, MONTH, DAY, year)  (ATTENTION! American Time Format)
#     The cookie, that will be set after a registration, will expire at this time +24h
#     After reaching this timestamp no further registration will be possible  
#link: Link to the event (string), relativ path to sub-directory
#path: Relativ path to CSV file (string), only necessary if used 




#DO NOT TOUCH
function handel($E, $short) {
       global $CONFIG_CONTACT;
       $info = $error = '';
       $enabled = true;
       if(!$E['active'] || time() > $E['uts']) {
              $info = 'Anmeldung noch nicht / nicht mehr möglich ';
              $enabled = false;
       }
       if (isset($_COOKIE["registered-{$short}"])) {
              $info = $info . 'Du bist schon angemeldet. ';
              $enabled = false;
       }
       if ($_SERVER['REQUEST_METHOD'] === 'POST' && $enabled) {
              $fpc = file_put_contents($E['path'], date("Y-m-d H:i:s") . "\n", FILE_APPEND); //Save registration in File
              if ($fpc) {
                  $info = "Erfolgreich angemeldet!";
                  setcookie("registered-{$short}", true, $E['uts'] + 86400); //Set 'already registred' Cookie, valid until 24h after UTS
                  $enabled = false;
                  }
              else {
                  $error = "Fehler beim Schreiben der Daten<br>Bitte kontaktiere <a href='mailto:{$CONFIG_CONTACT}'>{$CONFIG_CONTACT}</a>";
              }
       }
       return ['enabled' => $enabled, 'info' => $info, 'error' => $error];
}
?>
