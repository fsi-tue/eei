<?php

    // functions to use for registration
    
    function registerForOfflineEvent($event, $postParams){
        $phone = $postParams['phone'];
        $mail = $postParams['mail'];
        $name = $postParams['name'];
        $studiengang = $postParams['studiengang'];
        $semester = $postParams['semester'];
        $abschluss = $postParams['abschluss'];

        // TODO
        $file = "";

        // already registered
        if(strpos(file_get_contents($file), $mail) !== false){
            return "alreadyRegistered";
        }
    }


    function registerForOnlineEvent($event, $postParams){
        $mail = $postParams['mail'];

        // TODO
        $file = "";        

        // already registered
        if(strpos(file_get_contents($file), $mail) !== false){
            return "alreadyRegistered";
        }        
    }


?>