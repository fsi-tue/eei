<?php
require_once('config.php');

function replaceFirstOccurence($searchStr, $replacementStr, $sourceStr) {
        return (false !== ($pos = strpos($sourceStr, $searchStr))) ? substr_replace($sourceStr, $replacementStr, $pos, strlen($searchStr)) : $sourceStr;
}

# Echos the number of remaining spots for a event e
function getNumberOfRemainingSpots($E) {
    if($E['max_participants']) {
        $filepath = $E['path'];
        $HEADER_LINE_COUNT = 1;
        if(file_exists($filepath)) {
            $file = file( $filepath, FILE_SKIP_EMPTY_LINES);
            $spots = $E['max_participants'] - (count($file) - $HEADER_LINE_COUNT);
            if($spots <= 0) {
                $spots = 0;
            }
            return $spots;
        }
        else {
            return $E['max_participants'];
        }
    }
}

function writeHeader($file, $E) {
    clearstatcache();
    if(!filesize($E['path'])){
        if($E['course_required'])
            $headers = array("name", "mail", "studiengang", "abschluss", "semester");
        else
            $headers = array("name", "mail");
        if($E['food'])
            $headers[] = "essen";
        if($E['name'] === "Ersti WE")
            $headers[] = "fruehstueck";
        fputcsv($file, $headers);
    }
}

function sendRegistrationMail($recipient, $registration_id, $E) {
    $subject = "Registrierung zu {$E['name']}";
    $deleteRegistrationLink = "https://eei.fsi.uni-tuebingen.de/event.php?e={$E['link']}&r={$registration_id}";
    $msg = "Du hast dich erfolgreich zu {$E['name']} angemeldet.\nWenn du dich abmelden möchtest, klicke bitte auf den folgenden Link: {$deleteRegistrationLink}\n\nViele Grüße,\n{$SENDER_NAME}";
    $headers = "From:" . $SENDER_NAME . " <" . $SENDER_EMAIL . ">";
    mail($recipient, $subject, $msg, $headers);
}

function sendRegistrationDeletedMail($recipient, $E) {
    $subject = "Abmeldung zu {$E['name']}";
    $msg = "Du hast dich erfolgreich von {$E['name']} abgemeldet.\n\nViele Grüße,\n{$SENDER_NAME}";
    $headers = "From:" . $SENDER_NAME . " <" . $SENDER_EMAIL . ">";
    mail($recipient, $subject, $msg, $headers);
}

# Generates an ID
# It is used to identify the registration
function generateRegistrationIDFromData($data, $E) {
    // Use only "static" values from the event
    return hash('sha256', implode("", [...$data, $E["link"], $E["path"], $E["active"]]));
}

# Deletes a registration
function deleteRegistration($registration_id, $E) {
    $filepath = $E["path"];
    $file = fopen($filepath, "r");
    $data = array();
    $success = false;
    
    while (($line = fgetcsv($file)) !== false) {
        // if the registration hash matches the one we're looking for, skip it
        if (generateRegistrationIDFromData($line, $E) !== $registration_id) {
            // if it's not, add it to the new data array
            $data[] = $line;
        } else {
            $success = true;
        }
    }

    // close the file and open it for writing
    fclose($file);
    $file = fopen($filepath, 'w');

    // loop through the modified data array and write each line to the file
    foreach ($data as $line) {
        fputcsv($file, $line);
    }
    fclose($file);
    
    if ($success) {
        echo "<div class='block success'>Du hast dich erfolgreich abgemeldet.</div>";
        sendRegistrationDeletedMail($line[1], $E);
    } else {
        echo "<div class='block error'>Die Abmeldung war nicht erfolgreich. Bitte melde dich an {$CONFIG_CONTACT}.</div>";
    }
}

function showDeleteRegistration($registration_id, $E) {
    echo '<form action="event.php?e=' . $E['link'] . '&r=' . $registration_id . '" method="post">
        <div>Von der Veranstaltung ' . $E['name'] .  ' abmelden</div>
        <input type="hidden" name="registration_id" value="' . $registration_id . '">
        <input type="submit" name="delete_registration" value="Abmelden">';
    echo '</form>';
}

# Processes a registration
function register($E){
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $mail = filter_input(INPUT_POST, 'mail', FILTER_SANITIZE_EMAIL);

    if($E['course_required']){
        $studiengang = filter_input(INPUT_POST, 'studiengang', FILTER_SANITIZE_ENCODED);
        $abschluss = filter_input(INPUT_POST, 'abschluss', FILTER_SANITIZE_ENCODED);
        $semester =filter_input(INPUT_POST, 'semester', FILTER_SANITIZE_ENCODED);
    }
    if($E['food'])
        $essen = filter_input(INPUT_POST, 'essen', FILTER_SANITIZE_ENCODED);
    if($E['name'] === "Ersti WE")
        $fruehstueck = filter_input(INPUT_POST, 'fruehstueck', FILTER_SANITIZE_ENCODED);

    if(empty($mail) || empty($name) || ($E['course_required'] && (empty($studiengang) || empty($semester) || empty($abschluss)))){
        echo "<div class='block error'>Fehler. Du hast nicht alle erforderlichen Daten angegeben.</div>";
        return;
    }

    // already registered
    if(strpos(file_get_contents($E['path']), $mail) !== false){
        echo "<div class='block error'>Du bist zu dieser Veranstaltung bereits angemeldet.</div>";
        return;
    }

    $data = array();

    array_push($data, $name);
    array_push($data, $mail);

    if($E['course_required']){
        array_push($data, $studiengang);
        array_push($data, $abschluss);
        array_push($data, $semester);
    }
    if($E['food']){
        array_push($data, $essen);
    }
    if($E['name'] === "Ersti WE")
        array_push($data, $fruehstueck);

    $file = fopen($E['path'], "a");

    if($file === false){
        echo "<div class='block error'>Fehler beim Schreiben der Daten<br>Bitte probiere es noch einmal oder kontaktiere <a href='mailto:{$CONFIG_CONTACT}'>{$CONFIG_CONTACT}</a></div>";
        return;
    }

    // add CSV headers if file doesn't exist yet
    // check if file is empty, because we can't check if it exists because it was opened with fopen()
    writeHeader($file, $E);
    // use fputcsvRetVal to check if the write was successful
    $fputcsvRetVal = fputcsv($file, $data);
    fclose($file);
    
    if ($fputcsvRetVal !== false) {
        echo "<div class='block info'>Du hast dich erfolgreich zu dieser Veranstaltung angemeldet! Du erhältst einige Tage vor dem Event eine Mail.</div>";
        // Generate registration hash and send mail
        sendRegistrationMail($mail, generateRegistrationIDFromData($data, $E), $E);
    } else {
        echo "<div class='block error'>Fehler beim Schreiben der Daten<br>Bitte probiere es noch einmal oder kontaktiere {$CONFIG_CONTACT}.</div>";
    }
}

function showRegistration($E){
    global $CONFIG_CONTACT;
    if(time() < $E['start_of_registration']) {
        echo "<div class='block error'>Die Anmeldephase für diese Veranstaltung hat noch nicht angefangen.</div>";
        return;
    }

    if(time() >= $E['end_of_registration']) {
        echo "<div class='block error'>Die Anmeldephase für diese Veranstaltung ist vorüber.<br>
        Du erhältst in Kürze eine Mail</div>";
        return;
    }


    if($E['cancelled']){
        echo "<div class = 'block error'> {$E['name']} fällt leider aus.<br>
            Die Gründe sind entweder offensichtlich oder bei der Fachschaft unter <a href='mailto:{$CONFIG_CONTACT}'> {$CONFIG_CONTACT}</a> zu erfragen.</div>";
        return;
    }

    if($E['active']){
        // return if the maximum number of participants has been reached

        if(getNumberOfRemainingSpots($E) == 0) {
            echo "<div class = 'block error'>Für diese Veranstaltung sind bereits alle Plätze vergeben.</div>";
            return;
        }
        echo '
            <form method = "post" action = "#">
                Dein Name (Vor- und Nachname): <br>
                <input type="text" id="form-name" name="name" required size="30"><br><br>

                Mail-Adresse:<br>
                <input type="email" id="form-mail" name="mail" required size="30"><br><br>
        ';

        echo $E['course_required']?
                'Studiengang:<br>
                <label><input type="radio" class="form-studiengang" name="studiengang" value="Informatik" required> Informatik</label><br>
                <label><input type="radio" class="form-studiengang" name="studiengang" value="Lehramt"> Lehramt</label><br>
                <label><input type="radio" class="form-studiengang" name="studiengang" value="Bioinformatik"> Bioinformatik</label><br>
                <label><input type="radio" class="form-studiengang" name="studiengang" value="Medizininformatik"> Medizininformatik</label><br>
                <label><input type="radio" class="form-studiengang" name="studiengang" value="Medieninformatik"> Medieninformatik</label><br>
                <label><input type="radio" class="form-studiengang" name="studiengang" value="Machine Learning"> Machine Learning</label><br>
                <label><input type="radio" class="form-studiengang" name="studiengang" value="Kognitionswissenschaft"> Kognitionswissenschaft</label><br>
                <label><input type="radio" class="form-studiengang" name="studiengang" value="Nebenfach" required> Nebenfach<br></label><br>

                Abschluss:<br>
                <label><input type="radio" class="form-abschluss" name="abschluss" value="Bachelor" required> Bachelor</label><br>
                <label><input type="radio" class="form-abschluss" name="abschluss" value="Master"> Master</label><br><br>

                Semester:<br>
                <label><input type="radio" class="form-semester" name="semester" value="1" required> 1</label> <br>
                <label><input type="radio" class="form-semester" name="semester" value="2"> 2</label> <br>
                <label><input type="radio" class="form-semester" name="semester" value="3"> 3</label> <br>
                <label><input type="radio" class="form-semester" name="semester" value="viele"> viele <label/><br>'
        : '';
        echo ($E['food']) ?
                '<br>Essen:<br>
                <label><input type="radio" class="form-essen" name="essen" value="keine Präferenzen" required> keine Präferenzen</label><br>
                <label><input type="radio" class="form-essen" name="essen" value="Vegetarisch"> Vegetarisch</label><br>
                <label><input type="radio" class="form-essen" name="essen" value="Vegan"> Vegan</label><br>
                <label><input type="radio" class="form-essen" name="essen" value="kein Schwein"> kein Schwein</label><br>'
        : '';
        echo ($E['breakfast']) ?
                '<br>Frühstück:<br>
                <label><input type="radio" class="form-fruehstueck" name="fruehstueck" value="keine Präferenzen" required> keine Präferenzen</label> <br>
                <label><input type="radio" class="form-fruehstueck" name="fruehstueck" value="süß"> süß</label><br>
                <label><input type="radio" class="form"fruehstueck" name="fruehstueck" value="salzig"> salzig<label/><br>'
        : '';
        echo '
                <input type="submit" value="Senden" onclick="saveFormValues()">
            </form>
            <script type="text/javascript" src="../js/saveFormValues.js"></script>
        ';
    }
}
?>
