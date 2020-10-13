<?php
require_once('../config.php');
require_once('../registration.php');
$short = 'KT'; #Kürzel des Events
$E = $events[$short]; #select Event
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
            Die Anfi - Kneipentour <br>
            Tübingen ist übersät mit kleinen Kneipen und Bars, die das Nachtleben maßgeblich beeinflussen.
            Um den Stress des informationsgefüllten Tages etwas sacken zu lassen, laden wir dich zu einerausgiebigen Kneipentour ein, 
            bei der wir in Kleingruppen die verschiedenen Lokalitäten derTübinger Altstadt besuchen. 
            Bitte bringe genügend Bargeld mit, man in wenigen Tübinger Bars mit EC-Karte zahlen kann! – Volksbanken und Sparkassen finden sich bei Bedarf in derStadt<br>
            Um besser planen zu können bitten wir euch (unverbindlich) bescheid zu geben wenn ihr kommt. Hierfür reicht ein simpler klick auf den Button.<br>
            Es ist auch kein Problem mitzukommen falls ihr euch nicht angemeldet habt<br><br>
            Deine Stimme wird gespeichert, zusätzlich wird ein Cookie gesetzt um dich darauf hinzuweisen dass du dich schonmal angemeldet hast.<br><br>
        </div>
        <?php
            echo ($h['info'] == '' ? '' : "<div class='block info'>{$h['info']}</div>");
            echo ($h['error'] == '' ? '' : "<div class='block info'>{$h['error']}</div>");
        ?>
        <div class="block>">
            <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST')
                    registerForOfflineEvent($E);

                showOfflineRegistration();
            ?>
        </div>
    </div>
</body>
</html>
