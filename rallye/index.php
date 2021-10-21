<?php
require_once('../config.php');
$short = 'RY'; #Kürzel des Events
$meal = false;
$info = $error = '';
$E = $events[$short]; #select Event
$freeSpots = getNumberOfRemainingSpots($events[$short]);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/style.css<?php echo $FILE_REVISION; ?>">
    <title>Anfi  <?php echo "{$E['name']} - {$CONFIG_TERM}"; ?></title>
</head>
<body>
    <div id="center">
        <div class="block">
            <h1><?php echo "{$E['name']} - {$CONFIG_TERM}"; ?></h1>
            <h2 class="description icon clock"><?php echo $E['date'];?></h2>
            <h2 class="description icon marker"><?php echo $E['location'];?></h2>
            <?php echo $freeSpots;?>
            Die zweite Anfi Stadtrallye<br>
            Diese wird identisch zur ersten Rallye sein. Wir lassen dich und deine Kommilitonen gegeneinander in Teams antreten. 
            Dabei werdet ihr interessante, schöne und verstörende Ecken Tübingens kennen lernen, 
            dabei hoffentlich die Orientierung in eurer neuen Heimat etwas verbessern und Kontakte knüpfen.<br>
            Melde dich mit deinen Daten unten an, um genaue Informationen zu Treffpunkt und deiner Gruppe zu bekommen.<br><br>
            Nach der Rallye ziehen wir gemeinsam durch die Kneipen dieser Stadt. 
            <br>
        </div>
        <?php
            echo ($h['info'] == '' ? '' : "<div class='block info'>{$h['info']}</div>");
            echo ($h['error'] == '' ? '' : "<div class='block info'>{$h['error']}</div>");
        ?>
        <div class="info"><strong>Die Veranstaltung findet mittels 3G statt. Also Getestet, Geimpft, Genesen. Also bitte die Bescheinigungen bereithalten.</strong></div>
        <br>
        <div class="block>">
            <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST')
                    register($E, $meal);
                showRegistration($E, $meal);
            ?>
        </div>
    </div>
</body>
</html>
