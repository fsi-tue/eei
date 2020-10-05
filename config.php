<?php

#General Informations
$CONFIG_CONTACT = 'fsi@fsi.uni-tuebingen.de';
$CONFIG_TERM = 'WS 20/21'; # example: WS 19/20 or SS 20
$fp = "../../../eei-registration/"; #File Prefix

#Anfi WE
$WE = ["name" => 'Anfi WE', "icon" => 'house', "active" => false, "location" => 'Irgendwo im Wald', "date" => '14 - 17.02.20', 
       "uts" => mktime('18', '0', '0', '02', '14', '2020'), "link" => 'anfi-we/', "path" => "{$fp}anfi-we.csv"];

/* #Spieleabend NICHT Akademisch
$SN = ["name" => 'Spieleabend', "icon" => 'dice', "active" => false, "location" => 'Sand 14 - A301', "date" => '30.03.20', 
       "uts" => mktime('18', '0', '0', '03', '30', '2020'), "link" => 'spa/', "path" => "{$fp}anfi-sp.csv"];
#Filmeabend
$FA = ["name" => 'Filmeabend', "icon" => 'film', "active" => false, "location" => 'Sand 14 - A301', "date" => '31.03.20', 
       "uts" => mktime('18', '0', '0', '03', '31', '2020'), "link" => 'film/', "path" => "{$fp}anfi-film.csv"];
#Grillen
$GR = ["name" => 'Grillen', "icon" => 'grill', "active" => false, "location" => 'Wiese - Sand 14', "date" => '02.04.20', 
       "uts" => mktime('16', '0', '0', '04', '02', '2020'), "link" => 'grillen/', "path" => "{$fp}anfi-grill.csv"];
*/
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
$RD = ["name" => 'Stadtrallye digital', "icon" => 'route', "active" => true, "location" => 'wird nach Anmeldung mitgeteilt', "date" => '01.11.20 15 Uhr',
       "uts" => mktime('15', '0', '0', '11', '01', '2020'), "link" => 'rallye-digital/', "path" => "{$fp}anfi-rallye-digital.csv"];
#Kneipentour
$KT = ["name" => 'Kneipentour', "icon" => 'beer', "active" => true, "location" => 'wird nach Anmeldung mitgeteilt', "date" => '30.10.20 19 Uhr', 
       "uts" => mktime('19', '0', '0', '10', '30', '2020'), "link" => 'kneipentour/', "path" => "{$fp}anfi-kneipentour.csv"];
/* #Frühstück
$FR = ["name" => 'Frühstück', "icon" => 'food', "active" => false, "location" => 'Mensa Morgenstelle', "date" => '09.04.20', 
       "uts" => mktime('10', '0', '0', '04', '08', '2020'), "link" => 'fruehstueck/', "path" => "{$fp}anfi-fruestueck.csv"];

#Spieleabend Akademisch
$SA = ["name" => 'Spieleabend akademisch', "icon" => 'cap', "active" => false, "location" => 'Discord', "date" => '06.11.20 19 Uhr', 
       "uts" => mktime('19', '0', '0', '11', '06', '2020'), "link" => 'spa-akad/', "path" => "{$fp}anfi-sp-akad.csv"];
*/
#Spieleabend digital
$SA = ["name" => 'Spieleabend digital', "icon" => 'cap', "active" => true, "location" => 'Discord', "date" => '06.11.20 19 Uhr', 
       "uts" => mktime('19', '0', '0', '11', '06', '2020'), "link" => 'spa-digital/', "path" => "{$fp}anfi-sp-digital.csv"];


$events = ['RY' => $RY, 'WA' => $WA, 'KT' => $KT, 
           'RD' => $RD, 'SD' => $SD, 'WA1' => $WA1 ];  

#Each event is an array in the $events array
#To add an event copy the dummy and modify the values. Then, append this event to the $events array
#The oder in this array defines the shown order

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
