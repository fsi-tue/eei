<?php
require_once 'config.php';
require_once 'utils.php';
require_once 'i18n/i18n.php';
require_once 'event_type.php';

global $i18n, $FILE_REVISION;
?>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport'
          content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, minimal-ui'>
    <meta http-equiv='X-UA-Compatible' content='ie=edge'>
    <link rel='stylesheet' href="css/style.css<?= $FILE_REVISION ?>">
    <link rel='stylesheet' href="css/icons.css<?= $FILE_REVISION ?>">
    <title><?= $i18n['title'] ?></title>
</head>