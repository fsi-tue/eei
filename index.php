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
            <strong>Aufgrund der Unvorhersehbarkeit der Corona Pandemie können wir noch nicht abschätzen wann und wie Präsenzveranstaltungen stattfinden können.
            Die Veranstaltungen, die es betrifft sind Wanderung und (reale) Stadtrally.<br>
            (Für den Fall, dass Präsenzveranstaltungen stattfinden können behalten wir uns vor Personen höherer Semester, sowie Person, die bereits an einer anderen Präsenzveranstaltung teilgenommen haben auszuschließen. Evtl. gibt es auch eine Absage am Tag der Veranstaltung)</strong>
            <!--<strong>Bitte beachtet, dass aufgrund der erhöhten Inzidenz die Veranstaltungen mit weniger Menschen durchgeführt werden müssen. 
            Aufgrund der hohen Nachfrage bitten wir euch, dass ihr euch nur für eine Veranstaltung anmeldet. 
            (Darüber hinaus behalten wir es uns vor Personen von Veranstaltungen auszuschließen, die bereits an einer anderen Präsenzveranstaltung teilgenommen haben. 
            Evtl. erhaltet ihr auch eine Absage am Tag der Veranstaltung per Email.)</strong>--><br>
            Deine Daten werden wegen Coronaverordnungen gebraucht und bis 2 Wochen nach den Veranstaltungen gespeichert.<br>
            Sie werden außerdem, solltest du dich einmal angemeldet haben, lokal in deinem Browser gespeichert, so dass du dich bei weiteren Veranstaltungen schneller anmelden kannst.<br>
            Du kannst diese Daten durch einen Klick aus deinem Browser löschen:<br>
            <input id="btn-clr" type="submit" value="Löschen" onclick="!localStorage.clear() && alert('Daten erfolgreich aus dem Browser gelöscht.')">
        </div>
        <br><br>
        <a href="https://github.com/fsi-tue/eei">Source Code</a>
    </div>
</body>
</html>
