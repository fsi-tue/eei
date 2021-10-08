<?php

    error_reporting(E_ERROR);
    date_default_timezone_set("Europe/Berlin");

    $FILE_REVISION = "?v=" . file_get_contents(__DIR__ . "/.git/refs/heads/master", NULL, NULL, 0, 40);

    #General Informations
    $CONFIG_CONTACT = 'fsi@fsi.uni-tuebingen.de';
    $CONFIG_TERM = 'WS 21/22'; # example: WS 19/20 or SS 20
    // see https://www.php.net/manual/en/language.constants.predefined.php
    $fp = __DIR__ . "/../eei-registration/"; #File Prefix

    #Sommerfest
    $SO = ["name" => 'Sommerfest', "icon" => 'grill', "active" => true, "location" => 'Terrasse Sand', "date" => '25.09.21 ab 18:00',
    "online" => false, "cancelled" => false, "uts" => mktime('18', '0', '0', '09', '25', '2021'), "link" => 'sommerfest/', "path" => "{$fp}sommerfest.csv",
    "max_participants" => 260 , "uts_override" => true, "end_of_registration" => mktime('20', '0', '0', '09', '23', '2021')];

    #Anfi WE
    $WE = ["name" => 'Anfi WE', "icon" => 'house', "active" => true, "location" => 'Irgendwo im Wald', "date" => '14 - 17.02.20', 
    "online" => false, "cancelled" => false, "uts" => mktime('18', '0', '0', '09', '14', '2021'), "link" => 'anfi-we/', "path" => "{$fp}anfi-we.csv",
    "max_participants" => false, "uts_override" => false, "end_of_registration" => false];

    #Spieleabend NICHT Akademisch
    $SN = ["name" => 'Anfi-Spieleabend', "icon" => 'dice', "active" => true, "location" => 'Sand 14 - verschiedene Räume', "date" => '23.10.21 19 Uhr', 
    "online" => false, "cancelled" => false, "uts" => mktime('19', '0', '0', '10', '23', '2021'), "link" => 'spa/', "path" => "{$fp}anfi-sp.csv",
    "max_participants" => false, "uts_override" => false, "end_of_registration" => false];

    #Filmeabend
    $FA = ["name" => 'Filmeabend', "icon" => 'film', "active" => true, "location" => 'Sand 14 - A301', "date" => '31.03.21', 
    "online" => false, "cancelled" => false, "uts" => mktime('18', '0', '0', '10', '31', '2021'), "link" => 'film/', "path" => "{$fp}anfi-film.csv",
    "max_participants" => false, "uts_override" => false, "end_of_registration" => false];

    #Grillen
    $GR = ["name" => 'Anfi-Grillen', "icon" => 'grill', "active" => true, "location" => 'Terrasse - Sand 14', "date" => '12.10.21 17 Uhr', 
        "online" => false, "cancelled" => false, "uts" => mktime('17', '0', '0', '10', '12', '2021'), "link" => 'grillen/', "path" => "{$fp}anfi-grill.csv",
        "max_participants" => 50, "uts_override" => false, "end_of_registration" => false];

    #Kneipentour
    $KT = ["name" => 'Kneipentour', "icon" => 'beer', "active" => true, "location" => 'wird nach Anmeldung mitgeteilt', "date" => '30.10.21', 
    "online" => false, "cancelled" => false, "uts" => mktime('19', '0', '0', '10', '20', '2021'), "link" => 'kneipentour/', "path" => "{$fp}anfi-kneipentour.csv",
    "max_participants" => false, "uts_override" => false, "end_of_registration" => false];

    #Frühstück
    $FR = ["name" => 'Frühstück', "icon" => 'food', "active" => false, "location" => 'Mensa Morgenstelle', "date" => '09.04.20', 
    "online" => false, "cancelled" => false, "uts" => mktime('10', '0', '0', '09', '08', '2021'), "link" => 'fruehstueck/', "path" => "{$fp}anfi-fruehstueck.csv",
    "max_participants" => false, "uts_override" => false, "end_of_registration" => false];

    #Spieleabend Akademisch
    $SA = ["name" => 'Spieleabend akademisch', "icon" => 'cap', "active" => true, "location" => 'Discord', "date" => '06.11.20 19 Uhr', 
    "online" => false, "cancelled" => false, "uts" => mktime('19', '0', '0', '11', '06', '2021'), "link" => 'spa-akad/', "path" => "{$fp}anfi-sp-akad.csv",
    "max_participants" => false, "uts_override" => false, "end_of_registration" => false];

    #Spieleabend digital 0
    $SD0 = ["name" => 'Spieleabend digital 1', "icon" => 'cap', "active" => true, "location" => 'Discord', "date" => '16.04.2021 19:00 Uhr',
    "online" => true, "cancelled" => false, "uts" => mktime('19', '0', '0', '09', '16', '2021'), "link" => 'spa-digital0/', "path" => "{$fp}anfi-sp-digital-0.csv",
    "max_participants" => false, "uts_override" => false, "end_of_registration" => false];

    #Wanderung
    $WA0 = ["name" => 'Anfi-Wanderung', "icon" => 'hiking', "active" => true, "location" => 'Taubenhaus an der Neckarinsel', "date" => '16.10.21 14 Uhr',
    "online" => false, "cancelled" => false, "uts" => mktime('14', '0', '0', '10', '16', '2021'), "link" => 'wanderung/', "path" => "{$fp}anfi-wanderung.csv",
    "max_participants" => false, "uts_override" => false, "end_of_registration" => false];

    #Stadtrallye digital 0
    $RD0 = ["name" => 'Stadtrallye digital 1', "icon" => 'route', "active" => true, "location" => 'Discord', "date" => '21.04.21 17:00 Uhr',
    "online" => true, "cancelled" => false, "uts" => mktime('17', '0', '0', '10', '21', '2021'), "link" => 'rallye-digital0/', "path" => "{$fp}anfi-rallye-digital-0.csv",
    "max_participants" => false, "uts_override" => false, "end_of_registration" => false];

    #Stadtrallye
    $RY = ["name" => 'Anfi-Stadtrallye', "icon" => 'route', "active" => true, "location" => 'Taubenhaus an der Neckarinsel', "date" => '14.10.21 16 Uhr',
    "online" => false, "cancelled" => false, "uts" => mktime('16', '0', '0', '10', '14', '2021'), "link" => 'rallye/', "path" => "{$fp}anfi-rallye.csv",
    "max_participants" => 80, "uts_override" => false, "end_of_registration" => false];

    #Workshops während Vorkurs
    #git
    $WSGIT = ["name" => 'Workshop Git', "icon" => 'cap', "active" => true, "location" => 'Sand F119', "date" => '14.10.2021 10 Uhr',
    "online" => false, "cancelled" => false, "uts" => mktime('10', '0', '0', '10', '14', '2021'), "link" => 'workshopgit/', "path" => "{$fp}workshop-git.csv",
    "max_participants" => 40, "uts_override" => false, "end_of_registration" => false];
    #bash
    $WSBS = ["name" => 'Workshop bash', "icon" => 'cap', "active" => true, "location" => 'MVL (Raum folgt) per Mail', "date" => '14.10.2021 10 Uhr',
    "online" => false, "cancelled" => false, "uts" => mktime('10', '0', '0', '10', '14', '2021'), "link" => 'workshopbash/', "path" => "{$fp}workshop-bash.csv",
    "max_participants" => 40, "uts_override" => false, "end_of_registration" => false];
    #LaTeX
    $WSLT = ["name" => 'Workshop LaTeX', "icon" => 'cap', "active" => true, "location" => 'Sand F119', "date" => '14.10.2021 12:30 Uhr',
    "online" => false, "cancelled" => false, "uts" => mktime('12', '30', '0', '10', '14', '2021'), "link" => 'workshoplatex/', "path" => "{$fp}workshop-latex.csv",
    "max_participants" => 40, "uts_override" => false, "end_of_registration" => false];
    #Misc. Tools
    $WSDIV = ["name" => 'Workshop Diverse Tools', "icon" => 'cap', "active" => true, "location" => 'MVL 6 (Raum folgt per Mail)', "date" => '14.10.2021 12:30 Uhr',
    "online" => false, "cancelled" => false, "uts" => mktime('12', '30', '0', '10', '14', '2021'), "link" => 'workshopdiv/', "path" => "{$fp}workshop-div.csv",
    "max_participants" => 40, "uts_override" => false, "end_of_registration" => false];

    $events = [   'WSGIT' => $WSGIT,
    'WSBS' => $WSBS,
    'WSLT' => $WSLT,
    'WSDIV' => $WSDIV,
    'GR' => $GR,
    'SN' => $SN,
    'WA0' => $WA0,
    'RY' => $RY
    /* 'WE' => $WE,
        'FA' => $FA,
        'SN' => $SN,
        'GR' => $GR,
        'KT' => $KT,
        'FR' => $FR,
        'WA0' => $WA0,
        'SD0' => $SD0,
        'RD0' => $RD0*/
    ];  

    #Each event is an array in the $events array
    #To add an event copy the dummy and modify the values. Then, append this event to the $events array
    #The order in this array defines the shown order

    $dummy =  ["name" => '', "icon" => '', "active" => true, "location" => 'Test', "date" => 'DD.MM.YY HH', 
    "online" => 'false', "cancelled" => false, "uts" => mktime('17', '59', '0', '12', '31', '2021'), "link" => "subDir/", "path" => "{$fp}/filename.csv",
    "max_participants" => false, "uts_override" => false, "end_of_registration" => false];
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


    #DO NOT TOUCH
    function getNumberOfRemainingSpots($e) {
        if($e['max_participants']) {    
            $filepath = $e['path'];
            $HEADER_LINE_COUNT = 1;
            if(file_exists($filepath)) {
                $file = file( $filepath, FILE_SKIP_EMPTY_LINES);
                return "<h2 class=\"description\">Verbleibende Plätze:".(string)($e['max_participants'] - (count($file) - $HEADER_LINE_COUNT))."</h2>";
            } 
            else 
            {
                return "<h2 class=\"description\">Verbleibende Plätze:".(string)$e['max_participants']."</h2>";
            }
        }
    }

    function register($E, $meal){
        $subject = "Deine Registrierung zu {$E['name']}";
        $msg = "Du hast dich erfolgreich zu {$E['name']} angemeldet.\n";
        $from = "noreply@eei.fsi.uni-tuebingen.de";
        if($E['online'] === false){
            $name = $_POST['name'];
            $mail = $_POST['mail'];
            $phone = $_POST['phone'];
            if($E['name'] !== 'Sommerfest'){
                $studiengang = $_POST['studiengang'];
                $abschluss = $_POST['abschluss'];
                $semester = $_POST['semester'];
            }
            if($meal)
                $essen = $_POST['essen'];
            if($E['name'] === "Anfi WE")
                $fruehstueck = $_POST['fruehstueck'];

            if(empty($phone) || empty($mail) || empty($name) || ($E['name'] !== 'Sommerfest' && (empty($studiengang) || empty($semester) || empty($abschluss)))){
                echo "<div class='block error'>Fehler. Du hast nicht alle erforderlichen Daten angegeben.</div>";
                return;
            }
            $filepath = $E['path'];

            // already registered
            if(strpos(file_get_contents($filepath), $mail) !== false || strpos(file_get_contents($filepath), $phone) !== false){
                echo "<div class='block error'>Du bist zu dieser Veranstaltung bereits angemeldet.</div>";
                return;
            }

            $data = array();

            array_push($data, $name);
            array_push($data, $mail);
            array_push($data, $phone);
            if($E['name'] !== 'Sommerfest'){
                array_push($data, $studiengang);
                array_push($data, $abschluss);
                array_push($data, $semester);
            }
            if($meal){
                array_push($data, $essen);
            }
            if($E['name'] === "Anfi WE")
                array_push($data, $fruehstueck);

            $file = fopen($filepath, "a");
            
            if($file === false){
                echo "<div class='block error'>Fehler beim Schreiben der Daten<br>Bitte probiere es noch einmal oder kontaktiere <a href='mailto:{$CONFIG_CONTACT}'>{$CONFIG_CONTACT}</a></div>";
                return;
            }

            // add CSV headers if file doesn't exist yet
            // check if file is empty, because we can't check if it exists because it was opened with fopen()
            clearstatcache();
            if(!filesize($filepath)){
                if($E['name'] !== 'Sommerfest')
                    $headers = array("name", "mail", "phone", "studiengang", "abschluss", "semester");
                else
                    $headers = array("name", "mail", "phone");
                if($meal)
                    $headers[] = "essen";
                if($E['name'] === "Anfi WE")
                    $headers[] = "fruehstueck";
                fputcsv($file, $headers);
            }

            fputcsv($file, $data);

            fclose($file);

            echo "<div class='block info'>Du hast dich erfolgreich zu dieser Veranstaltung angemeldet! Du erhältst einige Tage vor dem Event eine Mail.</div>";
            // mail($mail, $subject, $msg, $from);
            
        }
        else{ #online
            $name = $_POST['name'];
            $mail = $_POST['mail'];
            $studiengang = $_POST['studiengang'];
            $semester = $_POST['semester'];
            $abschluss = $_POST['abschluss'];

            $filepath = $E['path'];

            if(empty($name) || empty($mail) || empty($studiengang) || empty($semester) || empty($abschluss)){
                echo "<div class='block error'>Fehler. Du hast nicht alle erforderlichen Daten angegeben.</div>";
                return;
            }

            $data = array();

            array_push($data, $name);
            array_push($data, $mail);
            array_push($data, $studiengang);
            array_push($data, $abschluss);
            array_push($data, $semester);


            $file = fopen($filepath, "a");

            if($file === false){
                echo "<div class='block error'>Fehler beim Schreiben der Daten<br>Bitte probiere es noch einmal oder kontaktiere <a href='mailto:{$CONFIG_CONTACT}'>{$CONFIG_CONTACT}</a></div>";
                return;
            }

            // add CSV headers if file doesn't exist yet
            // check if file is empty, because we can't check if it exists because it was opened with fopen()
            clearstatcache();
            if(!filesize($filepath)){
                $headers = array("name", "mail", "studiengang", "abschluss", "semester");
                fputcsv($file, $headers);
            }

            fputcsv($file, $data);

            fclose($file);

            echo "<div class='block info'>Du hast dich erfolgreich zu dieser Veranstaltung angemeldet! Du erhältst einige Tage vor dem Event eine Mail.</div>";
            //mail($mail, $subject, $msg, $from);
        }
    }

    function showRegistration($E, $meal){
        global $CONFIG_CONTACT;
        // override the 72 + 2 hour limit for certain events
        if($E['uts_override']) {
            if(time() >= $E['end_of_registration']) {
                echo "<div class='block error'>Die Anmeldephase für diese Veranstaltung ist vorüber.<br>
                Du erhältst in Kürze eine Mail</div>";
                return;
            }
        }
        else {
            // return if the event is only 72 + 2 hours ahead, i.e. don't show the registration anymore
            if((time() + (86400 * 3) + (3600 * 2)) >= $E["uts"]){
                echo "<div class='block error'>Die Anmeldephase für diese Veranstaltung ist vorüber.<br>
                    Du erhältst in Kürze eine Mail</div>";
                return;
            }
        }
        

        if($E['cancelled']){
            echo "<div class = 'block error'> {$E['name']} fällt leider aus.<br>
                Die Gründe sind entweder offensichtlich oder bei der Fachschaft unter <a href='mailto:{$CONFIG_CONTACT}'> {$CONFIG_CONTACT}</a> zu erfragen.</div>";
            return;
        }

        if($E['active']){
            // return if the maximum number of participants has been reached
            if($E['max_participants']) {    
                $filepath = $E['path'];
                $HEADER_LINE_COUNT = 1;
                if(file_exists($filepath)) {
                    $file = file( $filepath, FILE_SKIP_EMPTY_LINES);
                    if( (count($file) - $HEADER_LINE_COUNT) >= $E['max_participants'] ) {
                        echo "<div class = 'block error'>Für diese Veranstaltung sind bereits alle Plätze vergeben.</div>";
                        return;
                    }
                }
            }   

            
            echo '
                <form method = "post" action = "#">
                    Dein Name (Vor- und Nachname): <br>
                    <input type="text" id="form-name" name="name" required size="30"><br><br>

                    Mail-Adresse:<br>
                    <input type="email" id="form-mail" name="mail" required size="30"><br><br>
            ';
            echo $E['online'] === false ?
                    'Telefonnummer:<br>
                    <input type="tel" id="form-phone" name="phone" required minlength="5" size="30"><br><br>'
            : '';

            echo $E['name'] !== "Sommerfest" ?
                    'Studiengang:<br>
                    <input type="radio" class="form-studiengang" name="studiengang" value="Informatik" required> Informatik<br>
                    <input type="radio" class="form-studiengang" name="studiengang" value="Lehramt"> Lehramt<br>
                    <input type="radio" class="form-studiengang" name="studiengang" value="Bioinformatik"> Bioinformatik<br>
                    <input type="radio" class="form-studiengang" name="studiengang" value="Medizininformatik"> Medizininformatik<br>
                    <input type="radio" class="form-studiengang" name="studiengang" value="Medieninformatik"> Medieninformatik<br>
                    <input type="radio" class="form-studiengang" name="studiengang" value="Machine Learning"> Machine Learning<br>
                    <input type="radio" class="form-studiengang" name="studiengang" value="Kognitionswissenschaft"> Kognitionswissenschaft<br>
                    <input type="radio" class="form-studiengang" name="studiengang" value="Nebenfach" required> Nebenfach<br><br>

                    Abschluss:<br>
                    <input type="radio" class="form-abschluss" name="abschluss" value="Bachelor" required> Bachelor<br>
                    <input type="radio" class="form-abschluss" name="abschluss" value="Master"> Master<br><br>

                    Semester:<br>
                    <input type="radio" class="form-semester" name="semester" value="1" required> 1 <br>
                    <input type="radio" class="form-semester" name="semester" value="2"> 2 <br>
                    <input type="radio" class="form-semester" name="semester" value="viele"> viele <br>'
            : '';
            echo ($E['online'] === false && $meal === true) ?
                    '<br>Essen:<br>
                    <input type="radio" class="form-essen" name="essen" value="keine Präferenzen" required> keine Präferenzen <br>
                    <input type="radio" class="form-essen" name="essen" value="Vegetarisch"> Vegetarisch <br>
                    <input type="radio" class="form-essen" name="essen" value="Vegan"> Vegan <br>
                    <input type="radio" class="form-essen" name="essen" value="kein Schwein"> kein Schwein <br>'
            : '';
            echo ($E['name'] === "Anfi WE") ?
                    '<br>Frühstück:<br>
                    <input type="radio" class="form-fruehstueck" name="fruehstueck" value="keine Präferenzen" required> keine Präferenzen <br>
                    <input type="radio" class="form-fruehstueck" name="fruehstueck" value="süß"> süß <br>
                    <input type="radio" class="form"fruehstueck" name="fruehstueck" value="salzig"> salzig <br>'
            : '';
            echo '
                    <input type="submit" value="Senden" onclick="saveFormValuesSommerfest()">
                </form>
                <script type="text/javascript" src="../js/saveFormValues.js"></script>
            ';
        }
    }
?>
