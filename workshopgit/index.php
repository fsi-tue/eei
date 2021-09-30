<?php
require_once('../config.php');
$short = 'WSGIT'; #Kürzel des Events
$meal = false;
$info = $error = '';
$E = $events[$short]; #select Event
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/style.css<?php echo $FILE_REVISION; ?>">
    <title><?php echo "{$E['name']} - {$CONFIG_TERM}"; ?></title>
</head>
<body>
    <div id="center">
        <div class="block">
            <h1><?php echo "{$E['name']} - {$CONFIG_TERM}"; ?></h1>
            <h2 class="description icon clock"><?php echo $E['date'];?></h2>
            <h2 class="description icon marker"><?php echo $E['location'];?></h2>
            Workshop Git  <br>
            Git ist DAS Tool, welches dir beim Teamprojekt und auf der Arbeit viel Mühe spart.
            In diesem Workshop gibt es eine kurze Einführung in die Versionsverwaltung mit Git für Programmierprojekte und wie ihr es effektiv nutzen könnt.
            Hierbei wird es keine Theorievorlesung sein, sondern wir werden schnell mit praktischen Übungen anfangen.
            Der Workshop wird von Studierenden für Studierende durchgeführt.
            Melde dich mit deinen Daten unten an, um am Workshop teilzunehmen.<br>
            <b style='color:red !important;'>Ohne Anmeldung ist eine Teilnahme nicht möglich!</b><br>
            Diese Daten werden evtl. auch an das Gesundheitsamt weitergegeben. Solltest du damit nicht einverstanden sein oder falsche Daten angeben, kannst du nicht teilnehmen.<br><br>
            Workshop von Studenten für Studenten. <br>
        </div>
        <?php
            echo ($h['info'] == '' ? '' : "<div class='block info'>{$h['info']}</div>");
            echo ($h['error'] == '' ? '' : "<div class='block info'>{$h['error']}</div>");
        ?>
        <div class="block>">
            <?php
                if($_SERVER['REQUEST_METHOD'] === 'POST')
                    register($E, $meal);
                showRegistration($E, $meal);
            ?>
        </div>
    </div>
</body>
</html>
