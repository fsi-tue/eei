<?php
require_once('../config.php');
$short = 'WSPY2'; #Kürzel des Events
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
    <title><?php echo "{$E['name']} - {$CONFIG_TERM}"; ?></title>
</head>
<body>
    <div id="center">
        <div class="block">
            <h1><?php echo "{$E['name']} - {$CONFIG_TERM}"; ?></h1>
            <h2 class="description icon clock"><?php echo $E['date'];?></h2>
            <h2 class="description icon marker"><?php echo $E['location'];?></h2>
            <?php echo $freeSpots;?>
            Workshop Python First Project<br>
            Schnell eine Aufgabe automatisieren? Ein ML-Modell trainieren oder doch ein eigenständiges Programm entwickeln?
            Mit der vielseitigen und einfach zu erlernenden Programmiersprache "Python" lässt sich dies und vieles mehr erreichen.
            In diesem Workshop lernt ihr die Grundlagen, um eigenständig kleinere Skripte zu schreiben.
            Dies ist der Zweite von zwei Teilen und behandelt fortgeschrittenere Themen.<br>
            <b style='color:red !important;'>Dieser Workshop ist für Studenten im 2. Semester oder höher gedacht!</b><br>
            </div>
        <?php
            echo ($h['info'] == '' ? '' : "<div class='block info'>{$h['info']}</div>");
            echo ($h['error'] == '' ? '' : "<div class='block info'>{$h['error']}</div>");
        ?>
        <div class="info"><strong>Für die Veranstaltung gilt 3G</strong></div>
        <br>
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
