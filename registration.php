<?php

    require_once(__DIR__ . '/config.php');

    // functions to use for registration
    
    function registerForOfflineEvent($event){
        $phone = $_POST['phone'];
        $mail = $_POST['mail'];
        $name = $_POST['name'];
        $studiengang = $_POST['studiengang'];
        $semester = $_POST['semester'];
        $abschluss = $_POST['abschluss'];

        if(empty($phone) || empty($mail) || empty($name) || empty($studiengang) || empty($semester) || empty($abschluss)){
            echo "<div class='block error'>Fehler. Du hast nicht alle erforderlichen Daten angegeben.</div>";
            return;
        }


        $filepath = $event["path"];

        // already registered
        if(strpos(file_get_contents($filepath), $mail) !== false || strpos(file_get_contents($filepath), $phone) !== false){
            echo "<div class='block error'>Du bist zu dieser Veranstaltung bereits angemeldet.</div>";
            return;
        }

        $data = array();

        array_push($data, $name);
        array_push($data, $phone);
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
            $headers = array("name", "phone", "mail", "studiengang", "abschluss", "semester");
            fputcsv($file, $headers);
        }

        fputcsv($file, $data);

        fclose($file);

        echo "<div class='block info'>Du hast dich erfolgreich zu dieser Veranstaltung angemeldet! Du erhältst einige Tage vor dem Event eine Mail.</div>";
    }


    function registerForOnlineEvent($event){
        $mail = $_POST['mail'];
        $studiengang = $_POST['studiengang'];
        $semester = $_POST['semester'];
        $abschluss = $_POST['abschluss'];

        if(empty($mail) || empty($studiengang) || empty($semester) || empty($abschluss)){
            echo "<div class='block error'>Fehler. Du hast nicht alle erforderlichen Daten angegeben.</div>";
            return;
        }
        

        $filepath = $event["path"];        

        // already registered
        if(strpos(file_get_contents($filepath), $mail) !== false){
            echo "<div class='block error'>Du bist zu dieser Veranstaltung bereits angemeldet.</div>";
            return;
        }

        $data = array();

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
            $headers = array("mail", "studiengang", "abschluss", "semester");
            fputcsv($file, $headers);
        }

        fputcsv($file, $data);

        fclose($file);

        echo "<div class='block info'>Du hast dich erfolgreich zu dieser Veranstaltung angemeldet! Du erhältst einige Tage vor dem Event eine Mail.</div>";
    }

    function showOfflineRegistration($event){
        // return if the event is only 72 + 2 hours ahead, i.e. don't show the registration anymore
        if((time() + (86400 * 3) + (3600 * 2)) >= $event["uts"]){
            echo "<div class='block error'>Die Anmeldephase für diese Veranstaltung ist vorüber.<br>
                  Du erhältst in Kürze eine Mail</div>";
            return;
        }

        echo '
            <form method="post" action="#">
                Dein Name (Vor- und Nachname): <br>
                <input type="text" id="form-name" name="name" required size="30"><br><br>

                Telefonnummer:<br>
                <input type="tel" id="form-phone" name="phone" required minlength="5" size="30"><br><br>

                Mail-Adresse:<br>
                <input type="email" id="form-mail" name="mail" required size="30"><br><br>
                
                Studiengang:<br>
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
                <input type="radio" class="form-semester" name="semester" value="1" required> 1<br>
                <input type="radio" class="form-semester" name="semester" value="2"> 2<br>
                <input type="radio" class="form-semester" name="semester" value="Viele"> Viele<br><br>

                <input type="submit" value="Senden" onclick="saveFormValues()">
            </form>
            <script type="text/javascript" src="../js/saveFormValues.js"></script>
        ';
    }

    function showOnlineRegistration($event){
        // return if the event is only 72 + 2 hours ahead, i.e. don't show the registration anymore
        if((time() + (86400 * 3) + (3600 * 2)) >= $event["uts"]){
            echo "<div class='block error'>Die Anmeldephase für diese Veranstaltung ist vorüber.<br>
                  Du erhältst in Kürze eine Mail</div>";
            return;
        }

        echo '
            <form method="post" action="#">
                Dein Name (Vor- und Nachname): <br>
                <input type="text" id="form-name" name="name" required size="30"><br><br>

                Mail-Adresse:<br>
                <input type="email" id="form-mail" name="mail" required size="30"><br><br>
                
                Studiengang:<br>
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
                <input type="radio" class="form-semester" name="semester" value="1" required> 1<br>
                <input type="radio" class="form-semester" name="semester" value="2"> 2<br>
                <input type="radio" class="form-semester" name="semester" value="Viele"> Viele<br><br>

                <input type="submit" value="Senden" onclick="saveFormValues()">
            </form>
            <script type="text/javascript" src="../js/saveFormValues.js"></script>
        ';
    }
?>
