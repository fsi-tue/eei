<?php
require_once('localisation/localizer.php');
$localizer = new Localizer();
require_once("config.php");

$events = [
        # Kneipentour
        "KT" => [
            "link" => "KT",
            "name" => $localizer['kt_name'],
            "uts" => mktime('18', '30', '0', '04', '11', '2023'),
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}ersti-kneipentour.csv",
            "icon" => "beer",
            "location" => '',
            "date" => '11.04.23 18:30',
            "max_participants" => 84,
            "start_of_registration" => mktime('0', '0', '0', '04', '02', '2023'),
            "end_of_registration" => mktime('17', '59', '59', '04', '10', '2023'),
            "text" => $localizer['kt_text'],
            "info" => "",
        ],
        # Stadtrallye
        "RY" => [
            "link" => "RY",
            "name" => $localizer['ry_name'],
            "uts" => mktime('18', '30', '0', '04', '12', '2023'),
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}ersti-rallye.csv",
            "icon" => "route",
            "location" => '',
            "date" => '12.04.23 16:00',
            "max_participants" => 120,
            "start_of_registration" => mktime('0', '0', '0', '04', '05', '2023'),
            "end_of_registration" => mktime('23', '59', '59', '04', '11', '2023'),
            "text" => $localizer['ry_text'],
            # <br> Nach der Rallye ziehen wir gemeinsam durch die Kneipen dieser Stadt.",
            "info" => "",
        ],
        # Kastenlauf
        "KASTEN" => [
            "link" => "KASTEN",
            "name" => $localizer['kasten_name'],
            "uts" => mktime('18', '0', '0', '04', '14', '2023'),
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}kastenlauf.csv",
            "icon" => "beer",
            "location" => 'Rewe Weststadt <br> Schleifmühlenweg 36',
            "date" => '14.04.23 18:00',
            "max_participants" => 60,
            "start_of_registration" => mktime('0', '0', '0', '04', '07', '2023'),
            "end_of_registration" => mktime('23', '59', '59', '04', '13', '2023'),
            "text" => $localizer['kasten_text'],
            "info" => "",
        ],
        # Wanderung 1
        "WD1" => [
            "link" => "WD1",
            "name" => $localizer['wd1_name'],
            "uts" => mktime('11', '0', '0', '04', '15', '2023'),
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}ersti-wanderung1.csv",
            "icon" => "route",
            "location" => 'Neckarinsel',
            "date" => '15.04.23 11:00',
            "max_participants" => 120,
            "start_of_registration" => mktime('0', '0', '0', '04', '08', '2023'),
            "end_of_registration" => mktime('11', '0', '0', '04', '15', '2023'),
            "text" => $localizer['wd1_text'],
            "info" => "",
        ],
        "FILM" => [
            "link" => 'FILM',
            "name" => $localizer['film_name'],
            "uts" => mktime('19', '30', '0', '04', '17', '2023'),
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => FALSE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}filmeabend.csv",
            "icon" => 'film',
            "location" => 'Sand',
            "date" => '17.04.23 19:30',
            "max_participants" => 50 ,
            "start_of_registration" => mktime('0', '0', '0', '04', '10', '2023'),
            "end_of_registration" => mktime('23', '59', '59', '04', '16', '2023'),
            "text" => $localizer['film_text'],
            "info" => ""
        ],
        # Spieleabend
        "SP1" => [
            "link" => 'SP1',
            "name" => $localizer['sp1_name'],
            "uts" => mktime('19', '0', '0', '04', '19', '2023'),
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}ersti-spieleabend1.csv",
            "icon" => 'dice',
            "location" => 'Sand 14, A104',
            "date" => '19.04.23 ab 19:00',
            "max_participants" => 200 ,
            "start_of_registration" => mktime('0', '0', '0', '4', '12', '2023'),
            "end_of_registration" => mktime('19', '0', '0', '4', '19', '2023'),
            "text" => $localizer['sp1_text'],
            "info" => ""
        ],
        # Grillen
        "GR" => [
            "link" => 'GR',
            "name" => $localizer['gr_name'],
            "uts" => mktime('17', '0', '0', '04', '22', '2023'),
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}ersti-grillen.csv",
            "icon" => 'grill',
            "location" => 'Terrasse Sand',
            "date" => '22.04.23 ab 17:00',
            "max_participants" => 150 ,
            "start_of_registration" => mktime('0', '0', '0', '04', '15', '2023'),
            "end_of_registration" => mktime('23', '59', '59', '04', '21', '2023'),
            "text" => $localizer['gr_text'],
            "info" => ""
        ],
        # Wochenende/Hütte
        "HUETTE" => [
            "link" => "HUETTE",
            "name" => $localizer['huette_name'],
            "uts" => mktime('15', '30', '0', '04', '28', '2023'),
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => TRUE,
            "breakfast" => TRUE,
            "path" => "{$fp}ersti-huette.csv",
            "icon" => "home",
            "location" => 'Kalkweil',
            "date" => '28.04.23 - 30.04.23',
            "max_participants" => 40,
            "start_of_registration" => mktime('10', '0', '0', '04', '17', '2023'),
            "end_of_registration" => mktime('23', '59', '59', '04', '21', '2023'),
            "text" => $localizer['huette_text'],
            "info" => "",
        ],
        # Spieleabend #2
        "SP2" => [
            "link" => 'SP2',
            "name" => $localizer['sp2_name'],
            "uts" => mktime('19', '0', '0', '05', '02', '2023'),
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}ersti-spieleabend2.csv",
            "icon" => 'dice',
            "location" => 'Sand 14, A104',
            "date" => '02.05.23 ab 19:00',
            "max_participants" => 200 ,
            "start_of_registration" => mktime('0', '0', '0', '04', '25', '2023'),
            "end_of_registration" => mktime('19', '0', '0', '05', '02', '2023'),
            "text" => $localizer['sp2_text'],
            "info" => ""
        ],
        # Wanderung 2
        "WD2" => [
            "link" => "WD2",
            "name" => $localizer['wd2_name'],
            "uts" => mktime('11', '0', '0', '05', '06', '2023'),
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}ersti-wanderung2.csv",
            "icon" => "route",
            "location" => 'Neckarinsel',
            "date" => '06.05.23 11:00',
            "max_participants" => 120,
            "start_of_registration" => mktime('0', '0', '0', '04', '29', '2023'),
            "end_of_registration" => mktime('11', '0', '0', '05', '06', '2023'),
            "text" => $localizer['wd2_text'],
            "info" => "",
        ],
        /*
        # Sommerfest
        "SO" => [
            "link" => 'SO',
            "name" => 'Sommerfest',
            "uts" => mktime('18', '0', '0', '04', '20', '2023'),
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => FALSE,
            "food" => TRUE,
            "breakfast" => FALSE,
            "path" => "{$fp}sommerfest.csv",
            "icon" => 'grill',
            "location" => 'Terrasse Sand',
            "date" => '25.09.21 ab 18:00',
            "max_participants" => 260 ,
            "start_of_registration" => FALSE,
            "end_of_registration" => mktime('20', '0', '0', '09', '23', '2021')
        ],
        # Frühstück
        "FRUEH" => [
            "link" => "FRUEH",
            "name" => 'Frühstück & Uni-Führung',
            "uts" => mktime('18', '0', '0', '04', '20', '2023'),
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => TRUE,
            "breakfast" => TRUE,
            "path" => "{$fp}ersti-fruestueck.csv",
            "icon" => "food",
            "location" => 'Mensa Morgenstelle',
            "date" => '14.10.22 9:00',
            "max_participants" => 220,
            "start_of_registration" => FALSE,
            "end_of_registration" => FALSE,
            "text" => "Das Frühsück und Führung der Morgenstelle<br>
            Wir laden dich an diesem Morgen zu einem gemütlichen Frühstück ein!
            Dabei erfährst du einiges über die Uni, die Fachschaft und was dich in den nächsten Monaten erwartet – auch im Gespräch mit älteren Studierenden.
            Direkt im Anschluss kannst du dann noch zur offizielle Erstsemesterbegrüßung des Fachbereichs Informatik. Wir haben extra genug Zeit eingeplant. <br>
            Um besser planen und Plätze reservieren zu können, bitten wir euch Bescheid zu geben, wenn ihr kommt. <br>
            Es ist auch kein Problem mitzukommen falls ihr euch nicht angemeldet habt.",
            "info" => "",
        ],
        # Jugger Workshop
        "JUGGER" => [
            "link" => "JUGGER",
            "name" => 'Jugger Workshop',
            "uts" => mktime('18', '0', '0', '04', '20', '2023'),
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}workshop-jugger.csv",
            "icon" => "marker",
            "location" => 'Terasse Sand',
            "date" => '17.10.22 17:00',
            "max_participants" => 18,
            "start_of_registration" => FALSE,
            "end_of_registration" => mktime('24', '0', '0', '10', '14', '2022'),
            "text" => "Jugger-Workshop<br>
            Jugger ist ein actionreicher Teamsport bei dem es um Taktik, Fairness und Spielspaß geht.
            Es spielen zwei Teams gegeneinander mit dem Ziel, einen Ball (Jugg) möglichst oft im Tor (Mal) der Gegenseite zu platzieren.
            Außer dem Läufer besitzt jede/r Spielende eine Polsterwaffe (Pompfe) mit der andere Spielende abgetippt werden können.
            Um eine Idee von der Sportart zu bekommen findet ihr <a href='https://www.youtube.com/watch?v=-EVhMVWmdUw'>hier [YouTube]</a> ein Beispielvideo.<br>
            Für den Workshop sind Studierende aus allen Semestern willkommen.
            Falls ihr eine Brille tragt empfehlen wir euch für den Workshop auf Kontaktlinsen umzusteigen.
            Bringt ansonsten bitte feste Sportschuhe und ggf. wetterfeste Kleidung mit.",
            "info" => "",
        ],
        # Workshop Latex Basic
        "ws-latex-basic" => [
            "link" => "ws-latex-basic",
            "name" => 'Latex Basic',
            "uts" => mktime('18', '0', '0', '04', '20', '2023'),
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}workshop-latex-basic-ss23.csv",
            "icon" => "cap",
            "location" => 'Sand A301',
            "date" => '04.04.23 9-12',
            "max_participants" => 25,
            "start_of_registration" => FALSE,
            "end_of_registration" => mktime('23', '59', '59', '04', '02', '2023'),
            "text" => "Ob Übungsblatt oder Abschlussarbeit, im Laufe des Studiums müsst ihr öfters wissenschaftlich und mathematische Texte verfassen. Mit LaTeX habt ihr die Möglichkeit, diese schnell und professionell zu erstellen. Dieser Workshop bietet euch einen Einstieg in den Umgang mit LaTeX, Overleaf und TexStudio. Könnt ihr dies schon, gibt es am Nachmittag fortgeschrittene Themen.",
            "info" => "Dieser Workshop richtet sich NICHT an Bachelor Ersties. Alle anderen sind herzlichst eingeladen.",
        ],
        # Workshop Latex Advanced
        "ws-latex-advanced" => [
            "link" => "ws-latex-advanced",
            "name" => 'Latex Advanced',
            "uts" => mktime('18', '0', '0', '04', '20', '2023'),
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}workshop-latex-advanced-ss23.csv",
            "icon" => "cap",
            "location" => 'Sand A301',
            "date" => '04.04.23 14-17',
            "max_participants" => 25,
            "start_of_registration" => FALSE,
            "end_of_registration" => mktime('23', '59', '59', '04', '02', '2023'),
            "text" => "Ob Übungsblatt oder Abschlussarbeit, im Laufe des Studiums müsst ihr öfters wissenschaftlich und mathematische Texte verfassen. Mit LaTeX habt ihr die Möglichkeit, diese schnell und professionell zu erstellen. Dieser Workshop bietet euch einen Einstieg in den Umgang mit LaTeX, Overleaf und TexStudio. Dieser Kurs setzt gewisse Latex Grundkentnisse die ihr im Workshop Latex Basics erwerben kann.",
            "info" => "Dieser Workshop richtet sich NICHT an Bachelor Ersties. Alle anderen sind herzlichst eingeladen.",
        ],
        # Workshop Git Basic
        "ws-git-basic" => [
            "link" => "ws-git-basic",
            "name" => 'GIT Basic',
            "uts" => mktime('18', '0', '0', '04', '20', '2023'),
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}workshop-git-basic-ss23.csv",
            "icon" => "cap",
            "location" => 'Sand A301',
            "date" => '05.04.23 9-12',
            "max_participants" => 25,
            "start_of_registration" => FALSE,
            "end_of_registration" => mktime('23', '59', '59', '04', '02', '2023'),
            "text" => "Git ist DAS Tool, welches dir beim Teamprojekt und auf der Arbeit viel Mühe spart. In diesem Workshop gibt es eine kurze Einführung in die Versionsverwaltung mit Git für Programmierprojekte und wie ihr es effektiv nutzen könnt. Hierbei wird es keine reine Theorievorlesung sein, sondern auch eine Vielzahl an praktischen Übungen geben. Dies ist der Grundlagenkurs.",
            "info" => "Dieser Workshop richtet sich NICHT an Bachelor Ersties. Alle anderen sind herzlichst eingeladen.",
        ],
        # Workshop Git Advanced
        "ws-git-advanced" => [
            "link" => "ws-git-advanced",
            "name" => 'GIT Advanced',
            "uts" => mktime('18', '0', '0', '04', '20', '2023'),
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}workshop-git-advanced-ss23.csv",
            "icon" => "cap",
            "location" => 'Sand A301',
            "date" => '05.04.23 14-17',
            "max_participants" => 25,
            "start_of_registration" => FALSE,
            "end_of_registration" => mktime('23', '59', '59', '04', '02', '2023'),
            "text" => "Git ist DAS Tool, welches dir beim Teamprojekt und auf der Arbeit viel Mühe spart. In diesem Workshop gibt es eine kurze Einführung in die Versionsverwaltung mit Git für Programmierprojekte und wie ihr es effektiv nutzen könnt. Hierbei wird es keine reine Theorievorlesung sein, sondern auch eine Vielzahl an praktischen Übungen geben. Dieser Kurs baut auf Git Basic auf.",
            "info" => "Dieser Workshop richtet sich NICHT an Bachelor Ersties. Alle anderen sind herzlichst eingeladen.",
        ],
        # Workshop Python Basics
            "ws-python-basic" => [
            "link" => "ws-python-basic",
            "name" => 'Python Basic',
            "uts" => mktime('18', '0', '0', '04', '20', '2023'),
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}workshop-python-basic-ss23.csv",
            "icon" => "cap",
            "location" => 'Sand A301',
            "date" => '03.04.23 9-16',
            "max_participants" => 25,
            "start_of_registration" => FALSE,
            "end_of_registration" => mktime('23', '59', '59', '04', '02', '2023'),
            "text" => "Schnell eine Aufgabe automatisieren? Ein ML-Modell trainieren oder doch ein eigenständiges Programm entwickeln? Mit der vielseitigen und einfach zu erlernenden Programmiersprache “Python” lässt sich dies und vieles mehr erreichen. In diesem Workshop lernt ihr die Grundlagen, um eigenständig kleinere Skripte zu schreiben. Nachmittags werden am Beispiel von einer Datenquelle im Netz tiefer gehende Konzepte eingeführt.",
            "info" => "Dieser Workshop richtet sich NICHT an Bachelor Ersties. Alle anderen sind herzlichst eingeladen.",
        ],
        */
    ];
?>
