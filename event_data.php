<?php
require_once("config.php");

$events = [
        # Spieleabend
        "SP1" => [
            "link" => 'SP1',
            "name" => 'Spieleabend',
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}ersti-spieleabend1.csv",
            "icon" => 'dice',
            "location" => 'Sand 14, A104',
            "date" => '19.04.23 ab 19:00',
            "uts" => mktime('19', '0', '0', '4', '19', '2023'),
            "max_participants" => 200 ,
            "registration_override" => true,
            "end_of_registration" => mktime('19', '0', '0', '4', '19', '2022'),
            "text" => "Der Ersti Spieleabend<br>
            Wir möchten dich zu einem Spieleabend mit guter Gesellschaft und entspannter Atmosphäre auf dem Sand einladen.
            Für einige Spiele sowie Getränke (gegen einen kleinen Obolus) sorgt die Fachschaft.
            Wir freuen uns natürlich sehr, wenn du auch eigene Spiele mitbringst, obwohl unsere Sammlung schon beachtlich ist!
            Um besser planen zu können und euch eine kleine Errinerungsmail zu schicken, bitten wir euch sich anzumelden.",
            "info" => ""
        ],
        # Workshop Git Basic
        "ws-git-basic" => [
            "link" => "ws-git-basic",
            "name" => 'GIT Basic',
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}workshop-git-basic-ss23.csv",
            "icon" => "cap",
            "location" => 'Sand A301',
            "date" => '05.04.23 9-12',
            "uts" => mktime('9', '0', '0', '04', '05', '2023'),
            "max_participants" => 25,
            "registration_override" => TRUE,
            "end_of_registration" => mktime('23', '59', '59', '04', '02', '2023'),
            "text" => "Git ist DAS Tool, welches dir beim Teamprojekt und auf der Arbeit viel Mühe spart. In diesem Workshop gibt es eine kurze Einführung in die Versionsverwaltung mit Git für Programmierprojekte und wie ihr es effektiv nutzen könnt. Hierbei wird es keine reine Theorievorlesung sein, sondern auch eine Vielzahl an praktischen Übungen geben. Dies ist der Grundlagenkurs.",
            "info" => "Dieser Workshop richtet sich NICHT an Bachelor Ersties. Alle anderen sind herzlichst eingeladen.",
        ],
        # Workshop Git Advanced
        "ws-git-advanced" => [
            "link" => "ws-git-advanced",
            "name" => 'GIT Advanced',
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}workshop-git-advanced-ss23.csv",
            "icon" => "cap",
            "location" => 'Sand A301',
            "date" => '05.04.23 14-17',
            "uts" => mktime('14', '0', '0', '04', '05', '2023'),
            "max_participants" => 25,
            "registration_override" => TRUE,
            "end_of_registration" => mktime('23', '59', '59', '04', '02', '2023'),
            "text" => "Git ist DAS Tool, welches dir beim Teamprojekt und auf der Arbeit viel Mühe spart. In diesem Workshop gibt es eine kurze Einführung in die Versionsverwaltung mit Git für Programmierprojekte und wie ihr es effektiv nutzen könnt. Hierbei wird es keine reine Theorievorlesung sein, sondern auch eine Vielzahl an praktischen Übungen geben. Dieser Kurs baut auf Git Basic auf.",
            "info" => "Dieser Workshop richtet sich NICHT an Bachelor Ersties. Alle anderen sind herzlichst eingeladen.",
        ],
        # Workshop Python Basics
        "ws-python-basic" => [
            "link" => "ws-python-basic",
            "name" => 'Python Basic',
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}workshop-python-basic-ss23.csv",
            "icon" => "cap",
            "location" => 'Sand A301',
            "date" => '03.04.23 9-16',
            "uts" => mktime('9', '0', '0', '04', '03', '2023'),
            "max_participants" => 25,
            "registration_override" => TRUE,
            "end_of_registration" => mktime('23', '59', '59', '04', '02', '2023'),
            "text" => "Schnell eine Aufgabe automatisieren? Ein ML-Modell trainieren oder doch ein eigenständiges Programm entwickeln? Mit der vielseitigen und einfach zu erlernenden Programmiersprache “Python” lässt sich dies und vieles mehr erreichen. In diesem Workshop lernt ihr die Grundlagen, um eigenständig kleinere Skripte zu schreiben. Nachmittags werden am Beispiel von einer Datenquelle im Netz tiefer gehende Konzepte eingeführt.",
            "info" => "Dieser Workshop richtet sich NICHT an Bachelor Ersties. Alle anderen sind herzlichst eingeladen.",
        ],
        # Workshop Python Basics
        "ws-python-training" => [
            "link" => "ws-python-training",
            "name" => 'Python Training',
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}workshop-python-advanced-ss23.csv",
            "icon" => "cap",
            "location" => 'Sand A301',
            "date" => '03.04.23 14-17',
            "uts" => mktime('14', '0', '0', '04', '03', '2023'),
            "max_participants" => 25,
            "registration_override" => TRUE,
            "end_of_registration" => mktime('23', '59', '59', '04', '02', '2023'),
            "text" => "Du beherrscht die Grundlagen in Python? Hast ggf. den Workshop Phyhon Basics gehört? Dann können wir hier weiterführende Themen behandeln der Python Entwicklung behandeln. ",
            "info" => "Dieser Workshop richtet sich NICHT an Bachelor Ersties. Alle anderen sind herzlichst eingeladen.",
        ],

        # Workshop Latex Basic
        "ws-latex-basic" => [
            "link" => "ws-latex-basic",
            "name" => 'Latex Basic',
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}workshop-latex-basic-ss23.csv",
            "icon" => "cap",
            "location" => 'Sand A301',
            "date" => '04.04.23 9-12',
            "uts" => mktime('9', '0', '0', '04', '04', '2023'),
            "max_participants" => 25,
            "registration_override" => TRUE,
            "end_of_registration" => mktime('23', '59', '59', '04', '02', '2023'),
            "text" => "Ob Übungsblatt oder Abschlussarbeit, im Laufe des Studiums müsst ihr öfters wissenschaftlich und mathematische Texte verfassen. Mit LaTeX habt ihr die Möglichkeit, diese schnell und professionell zu erstellen. Dieser Workshop bietet euch einen Einstieg in den Umgang mit LaTeX, Overleaf und TexStudio. Könnt ihr dies schon, gibt es am Nachmittag fortgeschrittene Themen.",
            "info" => "Dieser Workshop richtet sich NICHT an Bachelor Ersties. Alle anderen sind herzlichst eingeladen.",
        ],
        # Workshop Latex Advanced
        "ws-latex-advanced" => [
            "link" => "ws-latex-advanced",
            "name" => 'Latex Advanced',
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}workshop-latex-advanced-ss23.csv",
            "icon" => "cap",
            "location" => 'Sand A301',
            "date" => '04.04.23 14-17',
            "uts" => mktime('14', '0', '0', '04', '04', '2023'),
            "max_participants" => 25,
            "registration_override" => TRUE,
            "end_of_registration" => mktime('23', '59', '59', '04', '02', '2023'),
            "text" => "Ob Übungsblatt oder Abschlussarbeit, im Laufe des Studiums müsst ihr öfters wissenschaftlich und mathematische Texte verfassen. Mit LaTeX habt ihr die Möglichkeit, diese schnell und professionell zu erstellen. Dieser Workshop bietet euch einen Einstieg in den Umgang mit LaTeX, Overleaf und TexStudio. Dieser Kurs setzt gewisse Latex Grundkentnisse die ihr im Workshop Latex Basics erwerben kann.",
            "info" => "Dieser Workshop richtet sich NICHT an Bachelor Ersties. Alle anderen sind herzlichst eingeladen.",
        ],
        /*
        # Sommerfest
        "SO" => [
            "link" => 'SO',
            "name" => 'Sommerfest',
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => FALSE,
            "food" => TRUE,
            "breakfast" => FALSE,
            "path" => "{$fp}sommerfest.csv",
            "icon" => 'grill',
            "location" => 'Terrasse Sand',
            "date" => '25.09.21 ab 18:00',
            "uts" => mktime('18', '0', '0', '09', '25', '2022'),
            "max_participants" => 260 ,
            "registration_override" => FALSE,
            "end_of_registration" => mktime('20', '0', '0', '09', '23', '2021')
        ],
        # Kastenlauf
        "KASTEN" => [
            "link" => "KASTEN",
            "name" => 'Kastenlauf',
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}kastenlauf.csv",
            "icon" => "beer",
            "location" => 'Rewe Weststadt <br> Schleifmühlenweg 36',
            "date" => '07.10.22 14:30',
            "uts" => mktime('14', '30', '0', '10', '07', '2022'),
            "max_participants" => 60,
            "registration_override" => FALSE,
            "end_of_registration" => FALSE,
            "text" => "Beim Kastenlauf geht es darum zusammen zu laufen, trinken und Spaß zu haben. Wir werden uns beim Rewe
            treffen und uns dort in 2 und 3er Gruppen aufteilen. Keine Angst wenn du noch keinen kennst, dort sind genug Leute die
            neu in Tübingen sind. Im Rewe werden wir uns einen Kasten Bier bzw. Limo kaufen je nach dem was euch lieber ist. Hälfte-Hälfte
            geht auch. Die kosten sind selbst zu tragen. Wenn alle dann bereit sind, wird eine Strecke abgelaufen. Wer nun zuerst im
            Ziel angelangt ist und den Kasten leer getrunken hat, hat gewonnen. Es gibt auch einen Preis. Denkt bitte daran Wasser mitzubringen,
            die Strecke ist ein ganzes Stück und zum Ausgleich auch nicht schlecht.
            <ul>
            <li> 4.8km Strecke
            <li> 2er Gruppe 0.3Liter, 3er Gruppe 0.5Liter
            <li> Preis für den Sieger
            </ul>",
            "info" => "",
        ],
        # Frühstück
        "FRUEH" => [
            "link" => "FRUEH",
            "name" => 'Frühstück & Uni-Führung',
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => TRUE,
            "breakfast" => TRUE,
            "path" => "{$fp}ersti-fruestueck.csv",
            "icon" => "food",
            "location" => 'Mensa Morgenstelle',
            "date" => '14.10.22 9:00',
            "uts" => mktime('9', '0', '0', '10', '14', '2022'),
            "max_participants" => 220,
            "registration_override" => FALSE,
            "end_of_registration" => FALSE,
            "text" => "Das Frühsück und Führung der Morgenstelle<br>
            Wir laden dich an diesem Morgen zu einem gemütlichen Frühstück ein!
            Dabei erfährst du einiges über die Uni, die Fachschaft und was dich in den nächsten Monaten erwartet – auch im Gespräch mit älteren Studierenden.
            Direkt im Anschluss kannst du dann noch zur offizielle Erstsemesterbegrüßung des Fachbereichs Informatik. Wir haben extra genug Zeit eingeplant. <br>
            Um besser planen und Plätze reservieren zu können, bitten wir euch Bescheid zu geben, wenn ihr kommt. <br>
            Es ist auch kein Problem mitzukommen falls ihr euch nicht angemeldet habt.",
            "info" => "",
        ],
         # Wanderung
         "WD" => [
            "link" => "WD",
            "name" => 'Wanderung',
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}ersti-wanderung.csv",
            "icon" => "route",
            "location" => 'Taubenhaus Neckarinsel (Ostende)',
            "date" => '15.10.22 10:00',
            "uts" => mktime('17', '0', '0', '10', '15', '2022'),
            "max_participants" => 120,
            "registration_override" => TRUE,
            "end_of_registration" => mktime('17', '0', '0', '10', '14', '2022'),
            "text" => "Die Ersti Wanderung<br>
            Eine Wanderung durch den Schönbuch und den umliegenden Wäldern Tübingens.
            Melde dich mit deinen Daten unten an, um genaue Informationen zu Treffpunkt und deiner Gruppe zu bekommen.",
            "info" => "",
        ],
         # Jugger Workshop
         "JUGGER" => [
            "link" => "JUGGER",
            "name" => 'Jugger Workshop',
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}workshop-jugger.csv",
            "icon" => "marker",
            "location" => 'Terasse Sand',
            "date" => '17.10.22 17:00',
            "uts" => mktime('17', '0', '0', '10', '17', '2022'),
            "max_participants" => 18,
            "registration_override" => TRUE,
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
        # Stadtrallye
        "RY" => [
            "link" => "RY",
            "name" => 'Stadtrallye',
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}ersti-rallye.csv",
            "icon" => "route",
            "location" => 'Taubenhaus an der Neckarinsel',
            "date" => '18.10.22 17:00',
            "uts" => mktime('17', '0', '0', '10', '18', '2022'),
            "max_participants" => 120,
            "registration_override" => FALSE,
            "end_of_registration" => FALSE,
            "text" => "Die Ersti Stadtrallye<br>
            Wir lassen dich und deine Kommilitonen gegeneinander in Teams antreten. Dabei werdet ihr interessante, schöne und verstörende Ecken Tübingens kennen lernen, dabei hoffentlich die Orientierung in eurer neuen Heimat etwas verbessern und Kontakte knüpfen.
            Melde dich mit deinen Daten unten an, um genaue Informationen zu Treffpunkt und deiner Gruppe zu bekommen.
            <br><br>
            Nach der Rallye ziehen wir gemeinsam durch die Kneipen dieser Stadt. ",
            "info" => "",
        ],
        # Kneipentour
        "KT" => [
            "link" => "KT",
            "name" => 'Kneipentour',
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}ersti-kneipentour.csv",
            "icon" => "beer",
            "location" => 'Neckarmüller',
            "date" => '19.10.22 18:30',
            "uts" => mktime('17', '0', '0', '10', '19', '2022'),
            "max_participants" => 120,
            "registration_override" => FALSE,
            "end_of_registration" => FALSE,
            "text" => "Die Ersti - Kneipentour<br>
            Tübingen ist übersät mit kleinen Kneipen und Bars, die das Nachtleben maßgeblich beeinflussen. Um den Stress des informationsgefüllten Tages etwas sacken zu lassen, laden wir dich zu einer ausgiebigen Kneipentour ein, bei der wir in Kleingruppen die verschiedenen Lokalitäten der tübinger Altstadt besuchen. Bitte bringe genügend Bargeld mit, da man in wenigen tübinger Bars mit EC-Karte zahlen kann! – Volksbanken und Sparkassen finden sich bei Bedarf in der Stadt.
            Melde dich mit deinen Daten unten an, um genaue Informationen zu Treffpunkt und deiner Gruppe zu bekommen.",
            "info" => "",
        ],
        # Grillen
        "GR" => [
            "link" => 'GR',
            "name" => 'Grillen',
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}ersti-grillen.csv",
            "icon" => 'grill',
            "location" => 'Terrasse Sand',
            "date" => '22.10.22 ab 18:00',
            "uts" => mktime('18', '0', '0', '20', '22', '2022'),
            "max_participants" => 150 ,
            "registration_override" => FALSE,
            "end_of_registration" => FALSE,
            "text" => "Das Ersti Grillen<br>
            Du hast keinen Bock auf Kochen? Dann bist du hier genau richtig! In geselliger Runde wird die Fachschaft mit dir grillen.
            Bringt dazu mit, was auch immer du zum Grillen brauchst, unser Gasgrill wartet auf dich.
            Bring bitte auch dein Besteck und Geschirr selbst mit!",
            "info" => ""
        ],
        # Spieleabend 2
        "SP2" => [
            "link" => 'SP2',
            "name" => 'Spieleabend',
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}ersti-spieleabend2.csv",
            "icon" => 'dice',
            "location" => 'Sand 14',
            "date" => '27.10.22 ab 19:00',
            "uts" => mktime('19', '0', '0', '10', '27', '2022'),
            "max_participants" => 200 ,
            "registration_override" => true,
            "end_of_registration" => mktime('19', '0', '0', '10', '26', '2022'),
            "text" => "Der 2te Ersti Spieleabend<br>
            Wir möchten dich zu dem zweiten (analog-) Spieleabend mit guter Gesellschaft und entspannter Atmosphäre auf dem Sand einladen.
            Für einige Spiele sowie Getränke und Knabberkram (gegeneinen kleinen Obolus) sorgt die Fachschaft.
            Wir freuen uns natürlich sehr, wenn du auch eigene Spiele mitbringst, obwohl unsere Sammlung schon beachtlich ist!
            Um besser planen zu können bitten wir euch, Bescheid zu geben wenn ihr kommt.
            Bitte kommt aufgrund der aktuellen Situation NUR falls ihr euch angemeldet habt.",
            "info" => ""
        ],
        # Wochenende/Hütte
        "HUETTE" => [
            "link" => "HUETTE",
            "name" => 'Hütte',
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => TRUE,
            "breakfast" => TRUE,
            "path" => "{$fp}ersti-huette.csv",
            "icon" => "home",
            "location" => 'TBA',
            "date" => '25.11.22 - 27.11.22',
            "uts" => mktime('12', '0', '0', '11', '25', '2022'),
            "max_participants" => 40,
            "registration_override" => TRUE,
            "end_of_registration" => mktime('12', '0', '0', '11', '10', '2022'),
            "text" => "Was gibt es besseres als eine Wochenende mit deinen Kommiliton:innen auf einer Hütte zu verbringen?",
            "info" => "",
        ],
        */
    ];
?>
