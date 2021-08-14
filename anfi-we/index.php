<?php
require_once('../config.php');
$short = 'WE'; #Kürzel des Events
$meal = true;
$info = $error = '';
$E = $events[$short]; #select Event
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/style.css">
    <!--link rel="stylesheet" href="../css/anfi-we.css"-->
    <title><?php echo "{$E['name']} -{$CONFIG_TERM}"; ?></title>
</head>
<body>
    <div id="center">
        <div class="block">
            <h1><?php echo "{$E['name']} - {$CONFIG_TERM}"; ?></h1>
            <h2 class="description icon clock"><?php echo $E['date'];?><?php echo $E['time'];?></h2>
            <h2 class="description icon marker"><?php echo $E['location'];?></h2>
            Das Anfi WE <br>
            Bitte trage deine Daten ein damit wir besser planen können.<br>
            Deine Daten werden gespeichert, vom FSI-Orga Team ausgewertet und nach dem Anfi-WE gelöscht.<br>
            Sie werden nicht an Dritte weitergegeben.<br>
        </div>
        <?php
            echo ($info == '' ? '' : "<div class='block info'>{$info}</div>");
            echo ($error == '' ? '' : "<div class='block info'>{$error}</div>");
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
