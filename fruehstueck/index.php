<?php
require_once('../config.php');
$short = 'FR'; #Kürzel des Events
$E = $events[$short]; #select Event
$h = handel($E, $short);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/style.css">
    <title>Anfi  <?php echo "{$E['name']} - {$CONFIG_TERM}"; ?></title>
</head>
<body>
    <div id="center">
        <div class="block">
            <h1><?php echo "{$E['name']} - {$CONFIG_TERM}"; ?></h1>
            <h2 class="description icon clock"><?php echo $E['date'];?></h2>
            <h2 class="description icon marker"><?php echo $E['location'];?></h2>
            Wir laden dich an diesem Morgen zu einem gemütlichen Frühstück ein!
            Dabei erfährst due iniges über die Uni, die Fachschaft und was dich in den nächsten Monaten erwartet – auch imGespräch mit älteren Studierenden. Außerdem wirst du durch Prof. Ostermann – er wird dieInformatik I Vorlesung halten – begrüßt. 
            Danach machen wir eine Führung über die Morgenstelle,damit du die wichtigsten Räume und Hörsäle kennen lernst. <br>
            Um besser planen zu können bitten wir euch (unverbindlich) bescheid zu geben wenn ihr kommt. Hierfür reicht ein simpler klick auf den Button.<br>
            Es ist auch kein Problem mitzukommen falls ihr euch nicht angemeldet habt<br><br>
            Deine Stimme wird gespeichert, zusätzlich wird ein Cookie gesetzt um dich darauf hinzuweisen dass du dich schonmal angemeldet hast.<br><br>
        </div>
        <?php
            echo ($h['info'] == '' ? '' : "<div class='block info'>{$h['info']}</div>");
            echo ($h['error'] == '' ? '' : "<div class='block info'>{$h['error']}</div>");
        ?>
        <div class="block>">
            <form method="post" action="#">
                <input type="submit" value="Anmelden" <?php echo $h['enabled'] ? '' : 'disabled' ?>></td></tr>
            </form>
        </div>
    </div>
</body>
</html>
