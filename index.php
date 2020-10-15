<?php
require_once('config.php');
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css<?php echo $FILE_REVISION; ?>">
    <link rel="stylesheet" href="css/index.css<?php echo $FILE_REVISION; ?>">
    <title>Anfi Veranstaltungen</title>
</head>
<body>
    <!-- Icons made by fontawesome.com under CC BY 4.0 License https://fontawesome.com/license/free -->
    <!-- The BBQ-Grill Icon is made by Smashicons from www.flaticon.com -->
    <div id="center">
        <h1>Anfi-Veranstaltungen - <?php echo $CONFIG_TERM?></h1>
        <div class="container">
        <?php 
        foreach ($events as $e) {
                echo "<a href='{$e['link']}'>";
                echo "  <div class='box icon {$e['icon']}'>";
                echo "     <p class='name'>{$e['name']}</p>";
                echo "     <p class='date'>{$e['date']}</p>";
                echo "  </div>";
                echo "</a>";
        }
?>
            <br>
            Bitte melde dich zu den Veranstaltungen einzeln an.<br>
            Deine Daten werden wegen Coronaverordnungen gebraucht und bis 2 Wochen nach den Veranstaltungen gespeichert.<br>
            Sie werden außerdem, solltest du dich einmal angemeldet haben, lokal in deinem Browser gespeichert, so dass du dich bei weiteren Veranstaltungen schneller anmelden kannst.<br>
            Du kannst diese Daten durch einen Klick aus deinem Browser löschen:<br>
            <input type="submit" value="Löschen" onclick="localStorage.clear()">            
        </div>
        <br><br>
        <a href="https://github.com/fsi-tue/eei">Source Code</a>
    </div>
</body>
</html>
