<?php
require_once('../config.php');
require_once('../registration.php');
$short = 'SD1'; #Kürzel des Events
$E = $events[$short]; #select Event
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/style.css<?php echo $FILE_REVISION; ?>">
    <title>Anfi <?php echo "{$E['name']} - {$CONFIG_TERM}"; ?></title>
</head>
<body>
    <div id="center">
        <div class="block">
            <h1><?php echo "{$E['name']} - {$CONFIG_TERM}"; ?></h1>
            <h2 class="description icon clock"><?php echo $E['date'];?></h2>
            <h2 class="description icon marker"><?php echo $E['location'];?></h2>
            Der digitale Anfi Spieleabend, dieses mal akademisch ;)<br>
            An diesem Nachmittag/Abend möchten wir dich zunächst auf Discord einladen,
            um in gemütlicher Runde mit anderen Kommilitonen online zu spielen.
            Beliebte Spiele sind skribbl.io, Curvefever, Among Us, Secret Hitler und Gartic Phone.
            Melde dich mit deinen Daten unten an, um genaue Informationen zum Discord-Server zu bekommen.<br><br>
        </div>
        <?php
            echo ($h['info'] == '' ? '' : "<div class='block info'>{$h['info']}</div>");
            echo ($h['error'] == '' ? '' : "<div class='block info'>{$h['error']}</div>");
        ?>
        <div class="block>">
            <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    registerForOnlineEvent($E);
                }
                showOnlineRegistration($E);
            ?>
        </div>
    </div>
</body>
</html>
