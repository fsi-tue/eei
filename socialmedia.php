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
$image_height = 1350;

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
$icon_draw->composite(Imagick::COMPOSITE_BLEND, $image_width - 468 - 40, 40, 468, 78, $icon_image);
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
$draw = new ImagickDraw();
$draw->setFillColor($style["text-color"]);
$draw->setFont($style["subheader-font-family"]);
$draw->setFontSize(24);
$draw->setTextAlignment(Imagick::ALIGN_CENTER);
$draw->annotation($image_width / 2, 360, "Bist Du bereit, Deine neuen Kommiliton:innen kennenzulernen?");
$image->drawImage($draw);

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

/* pill generation loop */
foreach(range(0, $pages - 1) as $page) {
    /* restore base image */
    $image = clone $baseImage;
    /* get sub array from events */
    $page_events = array_slice($events, $page * $events_per_page, $events_per_page);
    /* draw pills */
    $pos_y = 420;
    foreach($page_events as $event) {
        drawEventPillLine($image, $pos_y, 
            date("d.m.y", $event->getEventStartUTS()),
            $event->name,
            $event->icon);
        $pos_y += $offset_y;
    }
    /* draw footer */
    $draw = new ImagickDraw();
    $draw->setFillColor($style["text-color"]);
    $draw->setFont($style["subheader-font-family"]);
    $draw->setFontSize(32);
    $draw->setTextAlignment(Imagick::ALIGN_CENTER);
    if($page < ($pages - 1)) {
        /* NOT the last page */
        $draw->annotation($image_width / 2, $image_height - 100, "Wischen für mehr Events");
    } else {
        /* LAST PAGE */
        $draw->annotation($image_width / 2, $image_height - 120, "Anmeldung unter eei.fsi.uni-tuebingen.de");
        $draw->annotation($image_width / 2, $image_height - 80, "Link in Bio!");
    }
    $image->drawImage($draw);
    /* save image to tmp folder */
    $image->setImageFormat('png');
    file_put_contents("tmp/social-overview-page" . $page . ".png", $image);
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