<?php

    // functions to use for registration
    
    function registerForOfflineEvent($event){
        $phone = $postParams['phone'];
        $mail = $postParams['mail'];
        $name = $postParams['name'];
        $studiengang = $postParams['studiengang'];
        $semester = $postParams['semester'];
        $abschluss = $postParams['abschluss'];

        if(empty($phone) || empty($mail) || empty($name) || empty($studiengang) || empty($semester) || empty($abschluss)){
            echo "<div class='block info'>Fehler. Du hast nicht alle erforderlichen Daten angegeben.</div>";
            return;
        }


        $file = $event["path"];

        // already registered
        if(strpos(file_get_contents($file), $mail) !== false || strpos(file_get_contents($file), $phone) !== false){
            echo "<div class='block info'>Du bist zu dieser Veranstaltung bereits angemeldet.</div>";
            return;
        }

        $data = array();

        array_push($data, $name);
        array_push($data, $phone);
        array_push($data, $mail);
        array_push($data, $studiengang);
        array_push($data, $abschluss);
        array_push($data, $semester);


        $file = fopen($file, "a");

        if($file === false){
            echo "<div class='block info'>Fehler beim Schreiben der Daten<br>Bitte kontaktiere <a href='mailto:{$CONFIG_CONTACT}'>{$CONFIG_CONTACT}</a></div>";
            return;
        }

        // add CSV headers if file doesn't exist yet
        if(!file_exists($file)){
            $headers = array("name", "phone", "mail", "studiengang", "abschluss", "semester");
            fputcsv($file, $headers);
        }

        fputcsv($file, $data);

        fclose($file);

        echo "<div class='block info'>Du hast dich erfolgreich zu dieser Veranstaltung angemeldet! Du erhältst einige Tage vor dem Event eine Mail.</div>";
    }


    function registerForOnlineEvent($event){
        $mail = $postParams['mail'];
        $studiengang = $postParams['studiengang'];
        $semester = $postParams['semester'];
        $abschluss = $postParams['abschluss'];

        if(empty($mail) || empty($studiengang) || empty($semester) || empty($abschluss)){
            echo "<div class='block info'>Fehler. Du hast nicht alle erforderlichen Daten angegeben.</div>";
            return;
        }
        

        $file = $event["path"];        

        // already registered
        if(strpos(file_get_contents($file), $mail) !== false){
            echo "<div class='block info'>Du bist zu dieser Veranstaltung bereits angemeldet.</div>";
            return;
        }

        $data = array();

        array_push($data, $mail);
        array_push($data, $studiengang);
        array_push($data, $abschluss);
        array_push($data, $semester);


        $file = fopen($file, "a");

        if($file === false){
            echo "<div class='block info'>Fehler beim Schreiben der Daten<br>Bitte kontaktiere <a href='mailto:{$CONFIG_CONTACT}'>{$CONFIG_CONTACT}</a></div>";
            return;
        }

        // add CSV headers if file doesn't exist yet
        if(!file_exists($file)){
            $headers = array("mail", "studiengang", "abschluss", "semester");
            fputcsv($file, $headers);
        }

        fputcsv($file, $data);

        fclose($file);

        echo "<div class='block info'>Du hast dich erfolgreich zu dieser Veranstaltung angemeldet! Du erhältst einige Tage vor dem Event eine Mail.</div>";
    }

    function showOfflineRegistration(){
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
                <input type="radio" class="form-studiengang" name="studiengang" value="Kognitionswissenschaft"> Kognitionswissenschaft<br><br>

                Abschluss:<br>
                <input type="radio" class="form-abschluss" name="abschluss" value="Bachelor" required> Bachelor<br>
                <input type="radio" class="form-abschluss" name="abschluss" value="Master"> Master<br><br>

                Semester:<br>
                <input type="radio" class="form-semester" name="semester" value="1" required> 1<br>
                <input type="radio" class="form-semester" name="semester" value="2"> 2<br>
                <input type="radio" class="form-semester" name="semester" value="Viele"> Viele<br><br>

                <input type="submit">
            </form>
        ';
    }

    function showOnlineRegistration(){
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
                <input type="radio" class="form-studiengang" name="studiengang" value="Kognitionswissenschaft" Kognitionswissenschaft<br><br>

                Abschluss:<br>
                <input type="radio" class="form-abschluss" name="abschluss" value="Bachelor" required> Bachelor<br>
                <input type="radio" class="form-abschluss" name="abschluss" value="Master"> Master<br><br>

                Semester:<br>
                <input type="radio" class="form-semester" name="semester" value="1" required> 1<br>
                <input type="radio" class="form-semester" name="semester" value="2"> 2<br>
                <input type="radio" class="form-semester" name="semester" value="Viele"> Viele<br><br>

                <input type="submit">
            </form>
        ';
    }
?>