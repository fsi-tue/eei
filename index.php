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
    <title>FSI Veranstaltungen</title>
</head>
<body>
    <!-- Icons made by fontawesome.com under CC BY 4.0 License https://fontawesome.com/license/free -->
    <!-- The BBQ-Grill Icon is made by Smashicons from www.flaticon.com -->
    <div id="center">
        <h1>FSI-Veranstaltungen - <?php echo $CONFIG_TERM?></h1>
        <div class="container">
<?php
        function replaceFirstOccurence($searchStr, $replacementStr, $sourceStr) {
            return (false !== ($pos = strpos($sourceStr, $searchStr))) ? substr_replace($sourceStr, $replacementStr, $pos, strlen($searchStr)) : $sourceStr;
        }

        foreach ($events as $e) {
            // date in first line, time shall go in new line
            $date = $e['date'];
            $date = replaceFirstOccurence(" ", ",<br>", $date);
            if ($e['active'] == false) {
                echo "<a class='inactive' href='{$e['link']}'>";
                echo "  <div class='box inactive icon {$e['icon']}'>";
            } else {
                echo "<a href='{$e['link']}'>";
                echo "  <div class='box icon {$e['icon']}'>";
            }
            echo "     <p class='name'>{$e['name']}</p>";
            echo "     <p class='date'>{$date}</p>";
            echo "  </div>";
            echo "</a>";
        }
?>
            <br>
            <div class="info"><strong>Für die Präsenzveranstaltungen gilt 3G.</strong></div>
            <br>
            <div>
            Deine Daten werden aufgrund der Coronaverordnungen gebraucht und bis 2 Wochen nach den Veranstaltungen gespeichert.
            Sie werden außerdem, solltest du dich einmal angemeldet haben, lokal in deinem Browser gespeichert, so dass du dich bei weiteren Veranstaltungen schneller anmelden kannst.<br>
            Du kannst diese Daten durch einen Klick aus deinem Browser löschen:<br>
            <input id="btn-clr" type="submit" value="Löschen" onclick="!localStorage.clear() && alert('Daten erfolgreich aus dem Browser gelöscht.')">
    </div>
        </div>
        <br><br>
        <a href="https://github.com/fsi-tue/eei">Source Code</a>
    </div>
</body>
</html>
