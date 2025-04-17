<?php
require_once 'config.php';
require_once 'utils.php';
require_once 'event_type.php';
require_once 'i18n/i18n.php';

global $i18n, $CONFIG_TERM, $FILE_REVISION, $events;

/* 1080 x 1350 --> Portrait Mode */
/* 1080 x 1920 --> Story Mode */

$style = array(
    "background-color" => "#000080",
    "background-color-light" => "#ffffff",
    "text-color" => "#ffffff",
    "font-family" => "css/opensans/OpenSans-Bold.ttf",
    "subheader-font-family" => "css/opensans/OpenSans-Regular.ttf",
    "header-font-family" => "css/anton/Anton-Regular.ttf",
);
$image_width = 1080;
$image_height = 1920;

/* create base image */
$image = new Imagick();
$image->newImage($image_width, $image_height, $style["background-color"]);
$image->setImageFormat('png');

/* create fsi logo */
$icon_draw = new ImagickDraw();
$icon_image = new Imagick();
$icon_image->setBackgroundColor("transparent");
$icon_image->readImage("icons/fsi_logo.png");
$icon_image->setImageAlphaChannel(imagick::ALPHACHANNEL_ACTIVATE);
$icon_image->setBackgroundColor("transparent");
$icon_draw->composite(Imagick::COMPOSITE_BLEND, ($image_width - 468) / 2, $image_height - 200, 468, 78, $icon_image);
$image->drawImage($icon_draw);

/* create semester term */
$draw = new ImagickDraw();
$draw->setFillColor($style["text-color"]);
$draw->setFont($style["font-family"]);
$draw->setFontSize(35);
$draw->setTextAlignment(Imagick::ALIGN_CENTER);
$draw->annotation($image_width / 2, 180, strtoupper($CONFIG_TERM));
$image->drawImage($draw);

/* create header */
$draw = new ImagickDraw();
$draw->setFillColor($style["text-color"]);
$draw->setFont($style["header-font-family"]);
$draw->setFontSize(130);
$draw->setTextAlignment(Imagick::ALIGN_CENTER);
$draw->annotation($image_width / 2, 320, "ERSTI-PROGRAMM");
$image->drawImage($draw);

/* create subheader */
/*
$draw = new ImagickDraw();
$draw->setFillColor($style["text-color"]);
$draw->setFont($style["subheader-font-family"]);
$draw->setFontSize(24);
$draw->setTextAlignment(Imagick::ALIGN_CENTER);
$draw->annotation($image_width / 2, 360, "Bist Du bereit, Deine neuen Kommiliton:innen kennenzulernen?");
$image->drawImage($draw);
*/

/* preserve base image */
$baseImage = clone $image;

/* pill settings */
$offset_y = 100;
$events_per_page = 7;
$pages = intval(count($events) / $events_per_page + 1);

/* modify $events */
usort($events, function (Event $a, Event $b) {
    return $a->getEventStartUTS() - $b->getEventStartUTS();
});
$events = array_values($events);
/*
var_dump($events[0]);
die();
*/


foreach($events as $event) {
    /* restore base image */
    $image = clone $baseImage;
    /* create drawing context */
    $draw = new ImagickDraw();

    /* draw event title pill */
    $draw->setFillColor($style["background-color-light"]);
    $draw->roundRectangle(80, 500, $image_width - 80, 500 + 100, 80 / 2, 80 / 2);
    /* draw event title */
    $draw->setFont($style["font-family"]);
    $draw->setFillColor($style["background-color"]);
    $draw->setFontSize(64);
    $draw->setTextAlignment(Imagick::ALIGN_CENTER);
    $draw->annotation($image_width / 2, 500 + 64 + 10, strtoupper($event->name));

    /* draw place text*/
    $draw->setFillColor($style["text-color"]);
    $draw->setFontSize(64);
    $draw->setTextAlignment(Imagick::ALIGN_LEFT);
    $text = $event->location;
    /* draw place icon */
    $icon_image = new Imagick();
    $icon_image->setFormat('SVG');
    $icon_image->setBackgroundColor($style["background-color"]);
    $icon_image->readImageBlob(str_replace("currentColor", $style["background-color-light"], file_get_contents("icons/marker-solid.svg"))); 
    $icon_image->setImageAlphaChannel(imagick::ALPHACHANNEL_ACTIVATE);
    $icon_image->setBackgroundColor($style["background-color"]);
    /* calculate position */
    $posinfo = $image->queryFontMetrics($draw, $text);
    $line_width = $posinfo["textWidth"] + 100; // text + icon
    $draw->composite(Imagick::COMPOSITE_COPY, ($image_width - $line_width) / 2, 1100, 70, 80, $icon_image);
    $image->drawImage($draw);
    $draw->annotation(($image_width - $line_width) / 2 + 100, 1100 + 64, $text);

    /* draw time text */
    $draw->setFillColor($style["text-color"]);
    $draw->setFontSize(64);
    $draw->setTextAlignment(Imagick::ALIGN_LEFT);
    $text = date("d.m.y", $event->getEventStartUTS()) . " um " . date("H:i", $event->getEventStartUTS()) . " Uhr";
    /* draw time icon */
    $icon_image = new Imagick();
    $icon_image->setFormat('SVG');
    $icon_image->setBackgroundColor($style["background-color"]);
    $icon_image->readImageBlob(str_replace("currentColor", $style["background-color-light"], file_get_contents("icons/clock-regular.svg"))); 
    $icon_image->setImageAlphaChannel(imagick::ALPHACHANNEL_ACTIVATE);
    $icon_image->setBackgroundColor($style["background-color"]);
    /* calculate position */
    $posinfo = $image->queryFontMetrics($draw, $text);
    $line_width = $posinfo["textWidth"] + 100; // text + icon
    $draw->composite(Imagick::COMPOSITE_COPY, ($image_width - $line_width) / 2, 1300, 80, 80, $icon_image);
    $image->drawImage($draw);
    $draw->annotation(($image_width - $line_width) / 2 + 100, 1300 + 64, $text);

    /* draw icon */
    $icon_path = "icons/" . $event->icon . "-solid.svg";
    if(file_exists($icon_path)) {
        $icon_image = new Imagick();
        $icon_image->setFormat('SVG');
        $icon_image->setBackgroundColor($style["background-color"]);
        $icon_image->readImageBlob(str_replace("currentColor", $style["background-color-light"], file_get_contents($icon_path))); // hehe
        $icon_image->setImageAlphaChannel(imagick::ALPHACHANNEL_ACTIVATE);
        $icon_image->setBackgroundColor($style["background-color"]);
        $draw->composite(Imagick::COMPOSITE_COPY, ($image_width - 300) / 2, 700, 300, 300, $icon_image);
        $image->drawImage($draw);
    }

    $image->setImageFormat('png');
    file_put_contents("tmp/social-event-" . strtolower($event->link) . ".png", $image);
}

header('Content-type: image/png');
echo $image;


function drawEventPillLine($image, $pos_y, $date, $text, $icon) {
    global $image_width, $image_height, $style;
    $draw = new ImagickDraw();
    $space_to_borders = 80;
    $pill_height = 80;
    $font_size = 30;
    /* draw pills */
    $draw->setFillColor($style["background-color-light"]);
    $draw->roundRectangle($space_to_borders, $pos_y, 240, $pos_y + $pill_height, $pill_height / 2, $pill_height / 2);
    $draw->roundRectangle(260, $pos_y, $image_width - $space_to_borders, $pos_y + $pill_height, $pill_height / 2, $pill_height / 2);
    /* draw text */
    $draw->setFont($style["font-family"]);
    $draw->setFillColor($style["background-color"]);
    $draw->setFontSize($font_size);
    /* date */
    $draw->setTextAlignment(Imagick::ALIGN_CENTER);
    $draw->annotation($space_to_borders + (240 - $space_to_borders) / 2 , $pos_y + ($pill_height / 2) + ($font_size / 2), $date);
    /* text */
    $draw->setTextAlignment(Imagick::ALIGN_LEFT);
    $draw->annotation(260 + $pill_height / 2 , $pos_y + ($pill_height / 2) + ($font_size / 2), $text);    
    /* icon */
    $path = "icons/" . $icon . "-solid.svg";
    if(file_exists($path)) {
        $icon_image = new Imagick();
        $icon_image->setFormat('SVG');
        $icon_image->readImageBlob(str_replace("currentColor", $style["background-color"], file_get_contents($path))); // hehe
        $icon_image->setImageAlphaChannel(imagick::ALPHACHANNEL_ACTIVATE);
        $icon_image->setBackgroundColor("transparent");
        $draw->composite(Imagick::COMPOSITE_COPY, $image_width - $space_to_borders - 100, $pos_y + $pill_height * 0.125, $pill_height * 0.75 * (130.0/150.0), $pill_height * 0.75, $icon_image);
    }
    
    $image->drawImage($draw);
}

?>