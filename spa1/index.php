<?php
require_once('../config.php');
$short = 'SN1'; #Kürzel des Events
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
    <link rel="stylesheet" href="../css/style.css">
    <title>Ersti  <?php echo "{$E['name']} - {$CONFIG_TERM}"; ?></title>
</head>
<body>
    <div id="center">
        <div class="block">
            <h1><?php echo "{$E['name']} - {$CONFIG_TERM}"; ?></h1>
            <h2 class="description icon clock"><?php echo $E['date'];?></h2>
            <h2 class="description icon marker"><?php echo $E['location'];?></h2>
            <?php echo $freeSpots;?>
            Der Ersti Spieleabend  <br>
            Wir möchten dich zu einem kleinen (analog-) Spieleabend mit guter Gesellschaft und entspannter Atmosphäre auf dem Sand einladen.
            Für einige Spiele sowie Getränke und Knabberkram (gegeneinen kleinen Obolus) sorgt die Fachschaft. Wir freuen uns natürlich sehr, wenn du auch eigene Spiele mitbringst, obwohl unsere Sammlung schon beachtlich ist!
            
            Um besser planen zu können bitten wir euch, Bescheid zu geben wenn ihr kommt.<br>
            Bitte kommt aufgrund der aktuellen Situation NUR falls ihr euch angemeldet habt.<br><br>
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
