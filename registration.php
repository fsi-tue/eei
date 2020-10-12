<?php

    // functions to use for registration
    
    function registerForOfflineEvent($event, $postParams){
        $phone = $postParams['phone'];
        $mail = $postParams['mail'];
        $name = $postParams['name'];
        $studiengang = $postParams['studiengang'];
        $semester = $postParams['semester'];
        $abschluss = $postParams['abschluss'];

        if(empty($phone) || empty($mail) || empty($name) || empty($studiengang) || empty($semester) || empty($abschluss)){
            return "notAllOptions";
        }


        $file = $event["path"];

        // already registered
        if(strpos(file_get_contents($file), $mail) !== false){
            return "alreadyRegistered";
        }

        return "ok";
    }


    function registerForOnlineEvent($event, $postParams){
        $mail = $postParams['mail'];
        $studiengang = $postParams['studiengang'];
        $semester = $postParams['semester'];
        $abschluss = $postParams['abschluss'];

        if(empty($mail) || empty($studiengang) || empty($semester) || empty($abschluss)){
            return "notAllOptions";
        }
        

        $file = $event["path"];        

        // already registered
        if(strpos(file_get_contents($file), $mail) !== false){
            return "alreadyRegistered";
        }        

        return "ok";
    }

    function showOfflineRegistration(){
        echo '
            <form method="post" action="#">
                Dein Name (Vor- und Nachname): <br>
                <input type="text" id="form-name" name="name" required minlength="4" maxlength="15" size="15"><br><br>

                Telefonnummer:<br>
                <input type="tel" id="form-phone" name="phone" required minlength="4" maxlength="15" size="15"><br><br>

                Mail-Adresse:<br>
                <input type="email" id="form-mail" name="mail" required size="20"><br><br>
                
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
                <input type="text" id="form-name" name="name" required minlength="4" maxlength="15" size="15"><br><br>

                Mail-Adresse:<br>
                <input type="email" id="form-mail" name="mail" required size="20"><br><br>
                
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