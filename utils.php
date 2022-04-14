<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';

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

function sendMail($recipient, $E) {
    $subject = "Deine Registrierung zu {$E['name']}";
    $msg = "Du hast dich erfolgreich zu {$E['name']} angemeldet.\n";

    $mailer = new PHPMailer();
    //Tell PHPMailer to use SMTP
    $mailer->isSMTP();

    $mailer->Host = $SMTP_HOST;
    $mailer->Port = $SMTP_PORT;
    $mailer->SMTPAuth = true;
    $mailer->Username = $SMTP_USER;
    //Password to use for SMTP authentication
    $mailer->Password = $SMTP_PASS;
    //Set who the message is to be sent from
    $mailer->setFrom($SMTP_SENDER_EMAIL, $SMTP_SENDER_NAME);
    //Set who the message is to be sent to
    $mailer->addAddress($recipient, $recipient);
    //Set the subject and body
    $mailer->Subject = $subject;
    $mailer->Body = $msg;
    //send the message, check for errors
    var_dump($mailer->send());
    if (!$mailer->send()) {
        echo 'Du bist angemeldet, die Bestätigungsemail konnte jedoch nicht verschickt werden: ' . $mailer->ErrorInfo;
    }
}

# Processes a registration
function register($E){

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $mail = filter_input(INPUT_POST, 'mail', FILTER_SANITIZE_EMAIL);
    
    if($E['course_required']){
        $studiengang = filter_input(INPUT_POST, 'studiengang', FILTER_SANITIZE_ENCODED);
        $abschluss = filter_input(INPUT_POST, 'abschluss', FILTER_SANITIZE_ENCODED);
        $semester =filter_input(INPUT_POST, 'semester', FILTER_SANITIZE_NUMBER_INT);
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
    fputcsv($file, $data);
    fclose($file);
    sendMail($mail, $E);

    echo "<div class='block info'>Du hast dich erfolgreich zu dieser Veranstaltung angemeldet! Du erhältst einige Tage vor dem Event eine Mail.</div>";
}

function showRegistration($E){
    global $CONFIG_CONTACT;
    // override the 72 + 2 hour limit for certain events
    if($E['registration_override']) {
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
                <input type="radio" class="form-semester" name="semester" value="3"> 3 <br>
                <input type="radio" class="form-semester" name="semester" value="viele"> viele <br>'
        : '';
        echo ($E['food']) ?
                '<br>Essen:<br>
                <input type="radio" class="form-essen" name="essen" value="keine Präferenzen" required> keine Präferenzen <br>
                <input type="radio" class="form-essen" name="essen" value="Vegetarisch"> Vegetarisch <br>
                <input type="radio" class="form-essen" name="essen" value="Vegan"> Vegan <br>
                <input type="radio" class="form-essen" name="essen" value="kein Schwein"> kein Schwein <br>'
        : '';
        echo ($E['breakfast']) ?
                '<br>Frühstück:<br>
                <input type="radio" class="form-fruehstueck" name="fruehstueck" value="keine Präferenzen" required> keine Präferenzen <br>
                <input type="radio" class="form-fruehstueck" name="fruehstueck" value="süß"> süß <br>
                <input type="radio" class="form"fruehstueck" name="fruehstueck" value="salzig"> salzig <br>'
        : '';
        echo '
                <input type="submit" value="Senden" onclick="saveFormValues()">
            </form>
            <script type="text/javascript" src="../js/saveFormValues.js"></script>
        ';
    }
}
?>