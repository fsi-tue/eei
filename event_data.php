<?php
require_once("config.php");

#Sommerfest
$events = [
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
            "registration_override" => false,
            "end_of_registration" => mktime('20', '0', '0', '09', '23', '2021')
        ],
        "SP1" => [
            "link" => 'SP1',
            "name" => 'Ersti-Spieleabend',
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}spieleabend1.csv",
            "icon" => 'dice',
            "location" => 'Terrasse Sand',
            "date" => '25.09.21 ab 18:00',
            "uts" => mktime('18', '0', '0', '09', '25', '2022'),
            "max_participants" => 260 ,
            "registration_override" => true,
            "end_of_registration" => mktime('20', '0', '0', '09', '23', '2021'),
            "text" => "Der Ersti Spieleabend<br>
            Wir möchten dich zu einem kleinen (analog-) Spieleabend mit guter Gesellschaft und entspannter Atmosphäre auf dem Sand einladen.
            Für einige Spiele sowie Getränke und Knabberkram (gegeneinen kleinen Obolus) sorgt die Fachschaft.
            Wir freuen uns natürlich sehr, wenn du auch eigene Spiele mitbringst, obwohl unsere Sammlung schon beachtlich ist!
            Um besser planen zu können bitten wir euch, Bescheid zu geben wenn ihr kommt.
            Bitte kommt aufgrund der aktuellen Situation NUR falls ihr euch angemeldet habt.",
            "info" => "Für die Veranstaltung gilt 3G."
        ],
        "GR" => [
            "link" => 'GR',
            "name" => 'Grillen',
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}grillen.csv",
            "icon" => 'grill',
            "location" => 'Terrasse Sand',
            "date" => '25.09.21 ab 18:00',
            "uts" => mktime('18', '0', '0', '09', '25', '2022'),
            "max_participants" => 150 ,
            "registration_override" => false,
            "end_of_registration" => mktime('20', '0', '0', '09', '23', '2021'),
            "text" => "Das Ersti Grillen  <br>
            Du hast keinen Bock auf Kochen? Dann bist du hier genau richtig! In geselliger Runde wird die Fachschaft mit dir grillen. 
            Bringt dazu mit, was auch immer du zum Grillen brauchst, unser Gasgrill wartet auf dich. 
            Bring bitte auch dein Besteck und Geschirr selbst mit!",
            "info" => "Für die Veranstaltung gilt 3G."
        ],
        "RY" => [
            "link" => "RY",
            "name" => 'Ersti-Stadtrallye',
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}ersti-rallye.csv",
            "icon" => "route",
            "location" => 'Taubenhaus an der Neckarinsel',
            "date" => '27.10.21 17 Uhr',
            "uts" => mktime('17', '0', '0', '10', '27', '2021'),
            "max_participants" => 120,
            "registration_override" => false,
            "end_of_registration" => false,
            "text" => "Die Ersti Stadtrallye<br>
            Wir lassen dich und deine Kommilitonen gegeneinander in Teams antreten. Dabei werdet ihr interessante, schöne und verstörende Ecken Tübingens kennen lernen, dabei hoffentlich die Orientierung in eurer neuen Heimat etwas verbessern und Kontakte knüpfen.
            Melde dich mit deinen Daten unten an, um genaue Informationen zu Treffpunkt und deiner Gruppe zu bekommen.
            <br><br>
            Nach der Rallye ziehen wir gemeinsam durch die Kneipen dieser Stadt. ",
            "info" => "Für die Veranstaltung gilt 3G.",
        ],
        "KT" => [
            "link" => "KT",
            "name" => 'Ersti-Kneipentour',
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}ersti-kneipentour.csv",
            "icon" => "route",
            "location" => 'Neckarmüller',
            "date" => '27.10.21 17 Uhr',
            "uts" => mktime('17', '0', '0', '10', '27', '2021'),
            "max_participants" => 120,
            "registration_override" => false,
            "end_of_registration" => false,
            "text" => "Die Ersti - Kneipentour<br>
            Tübingen ist übersät mit kleinen Kneipen und Bars, die das Nachtleben maßgeblich beeinflussen. Um den Stress des informationsgefüllten Tages etwas sacken zu lassen, laden wir dich zu einer ausgiebigen Kneipentour ein, bei der wir in Kleingruppen die verschiedenen Lokalitäten der tübinger Altstadt besuchen. Bitte bringe genügend Bargeld mit, da man in wenigen tübinger Bars mit EC-Karte zahlen kann! – Volksbanken und Sparkassen finden sich bei Bedarf in der Stadt.
            Melde dich mit deinen Daten unten an, um genaue Informationen zu Treffpunkt und deiner Gruppe zu bekommen.",
            "info" => "Für die Veranstaltung gilt 3G.",
        ],
        "WD" => [
            "link" => "WD",
            "name" => 'Ersti-Wanderung',
            "active" => TRUE,
            "cancelled" => FALSE,
            "course_required" => TRUE,
            "food" => FALSE,
            "breakfast" => FALSE,
            "path" => "{$fp}ersti-kneipentour.csv",
            "icon" => "route",
            "location" => 'Neckarmüller',
            "date" => '27.10.21 17 Uhr',
            "uts" => mktime('17', '0', '0', '10', '27', '2021'),
            "max_participants" => 120,
            "registration_override" => false,
            "end_of_registration" => false,
            "text" => "Die Ersti Wanderung<br>
            Eine Wanderung durch den Schönbuch und den umliegenden Wäldern Tübingens.
            Melde dich mit deinen Daten unten an, um genaue Informationen zu Treffpunkt und deiner Gruppe zu bekommen.",
            "info" => "Für die Veranstaltung gilt 3G.",
        ],

    ];
?>