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
                <input type="text" id="form-name" name="name" required minlength="4" maxlength="15" size="15">
                <input type="text" id="form-phone" name="phone" required minlength="4" maxlength="15" size="15">
                <input type="text" id="form-mail" name="mail" required size="20">
                <input list="studiengang" id="form-studiengang" name="studiengang" required>
                    <datalist id="form-studiengang">
                        <option value="Informatik">
                        <option value="Lehramt">
                        <option value="Bioinformatik">
                        <option value="Medizininformatik">
                        <option value="Medieninformatik">
                        <option value="Kognitionswissenschaft">
                    </datalist>
                <input type="text" id="form-abschluss" name="abschluss" required>
                    <datalist id="form-abschluss">
                        <option value="Bachelor">
                        <option value="Master">
                    </datalist>
                <input type="text" id="form-semester" name="semester" required>
                    <datalist id="form-semester">
                        <option value="1">
                        <option value="2">
                    </datalist>
                <input type="submit">
            </form>
        ';
    }

    function showOnlineRegistration(){
        echo '
            <form method="post" action="#">
                <input type="text" id="form-mail" name="mail" required size="20">
                <input list="studiengang" id="form-studiengang" name="studiengang" required>
                    <datalist id="form-studiengang">
                        <option value="Informatik">
                        <option value="Lehramt">
                        <option value="Bioinformatik">
                        <option value="Medizininformatik">
                        <option value="Medieninformatik">
                        <option value="Kognitionswissenschaft">
                    </datalist>
                <input type="text" id="form-abschluss" name="abschluss" required>
                    <datalist id="form-abschluss">
                        <option value="Bachelor">
                        <option value="Master">
                    </datalist>
                <input type="text" id="form-semester" name="semester" required>
                    <datalist id="form-semester">
                        <option value="1">
                        <option value="2">
                    </datalist>
                <input type="submit">
            </form>
        ';
    }
?>