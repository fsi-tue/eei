<?php
$short = 'WE'; #Kürzel des Events

require_once('../config.php');
$enabled = true;
$info = $error = '';
$E = $events[$short]; #select Event

if(!$E['active']) {
    $info = 'Anmeldung noch nicht / nicht mehr möglich ';
    $enabled = false;
}
if (isset($_COOKIE["registered-{$short}"])) {
    $info = $info . 'Du bist schon angemeldet. ';
    $enabled = false;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $enabled) {
    
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $matrikel = filter_input(INPUT_POST, 'matrikel', FILTER_SANITIZE_NUMBER_INT);
        $alter = filter_input(INPUT_POST, 'alter', FILTER_SANITIZE_NUMBER_INT);
        $essen = filter_input(INPUT_POST, 'essen', FILTER_SANITIZE_NUMBER_INT);
        $fruehstuek = filter_input(INPUT_POST, 'fruehstuek', FILTER_SANITIZE_NUMBER_INT);
        $sonstiges = filter_input(INPUT_POST, 'sonstiges', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        $name = str_replace(";", ",", $name);
        $sonstiges = str_replace(";", ",", $sonstiges);
        
        if($name != '' && $email != '' && $matrikel != '' && $alter != '' && $essen != '' && $fruehstuek != '') {
            $fpc = file_put_contents($E['path'], $name . ";" . $email . ";" . $matrikel . ";" . $alter . ";" .  
                                     $essen . ";"  . $fruehstuek . ";" .  $sonstiges . "\n", FILE_APPEND);
            if ($fpc) {
                $info = "Erfolgreich angemeldet!";
                setcookie("registered-{$short}", true, $E['uts']);
                $enabled = false;
            }
            else {
                $error = "Fehler beim Schreiben der Daten<br>Bitte kontaktiere <a href='mailto:{$CONFIG_CONTACT}'>{$CONFIG_CONTACT}</a>";
            }
       }
    else {
        $error = "Daten sind nicht vollständig!";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/anfi-we.css">
    <title>Anfi-WE <?php echo $CONFIG_TERM; ?> Anmeldung</title>
</head>
<body>
    <div id="center">
        <div class="block">
            <h1>Anfi-Wochenende <?php echo $CONFIG_TERM; ?></h1>
            Anfi WE am <?php echo $E['date'];?>
            Bitte trage deine Daten ein damit wir besser planen können.<br>
            Deine Daten werden gespeichert, vom FSI-Orga Team ausgewertet und nach dem Anfi-WE gelöscht.<br>
            Sie werden nicht an Dritte weitergegeben.<br>
            Zusätzlich wird ein Cookie gesetzt um dich darauf hinzuweisen dass du dich schonmal angemeldet hast.
        </div>
        <?php
            echo ($info == '' ? '' : "<div class='block info'>{$info}</div>");
            echo ($error == '' ? '' : "<div class='block info'>{$error}</div>");
        ?>
        <div class="block>">
            <form method="post" action="#">
                <table>
                    <tr><td class="ar">Name</td><td><input type="text" name="name" maxlength="30" required></td></tr>
                    <tr><td class="ar">Matrikelnummer</td><td><input type="number" min="3000000" max="6000000" name="matrikel" required></td></tr>
                    <tr><td class="ar">Email</td><td><input type="email" name="email" maxlength="70" required></td></tr>
                    <tr><td class="ar">Volljährig?</td><td>
                        <select name="alter" required>
                            <option value="">Bitte Auswählen</option>
                            <option value="1">Ja</option>
                            <option value="0">Nein</option>
                        </select></td></tr>
                    <tr><td class="ar">Essen</td><td>
                        <select name="essen" required>
                            <option value="">Bitte Auswählen</option>
                            <option value="1">Vegetarisch</option>
                            <option value="2">Vegan</option>
                            <option value="3">kein Schwein</option>
                            <option value="0">keine Präferenzen</option>
                        </select></td></tr>
                    <tr><td class="ar">Frühstück</td><td>
                        <select name="fruehstuek" required>
                            <option value="">Bitte Auswählen</option>
                            <option value="1">Süß</option>
                            <option value="2">Salzig</option>
                            <option value="0">keine Präferenzen</option>
                        </select></td></tr>
                    <tr><td class="ar">Anmerkungen</td><td><input type="text" name="sonstiges" maxlength="150"></td></tr>
                    <tr><td></td><td class="al">
                        <input type="submit" value="Anmelden" <?php echo $enabled ? '' : 'disabled' ?>></td></tr>
                </table>
            </form>
        </div>
    </div>
</body>
</html>
