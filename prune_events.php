<?php
/* Author: Lukas Oertel git@luoe.dev
 *
 * Deletes old events from EEI, this script is called by Github Actions
 */

require_once('config.php');
require_once('event_data.php');

foreach ($events as &$event_id){
        // delete list of participants if event was more than two weeks ago
        echo file_get_contents($event_id["path"]);
        if(file_exists($event_id['path']) && (time() + (86400 * 14))  >= $event_id["uts"]){
                if(unlink($event_id["path"])){
			echo("deleted " . $event_id['path'] . PHP_EOL);
		} else {
			http_response_code(500);
			exit("File could not be deleted. Possible file permission problem (uid/gid for PHP-FPM instances: 82)");
		}
	}
	echo "Exiting...";
}
