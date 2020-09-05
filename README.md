# eei
Erst Einführungs Interface

Each event is an array in the $events array
To add an event copy the dummy and modify the values. Then, append this event to the $events array
The oder in this array defines the shown order

$dummy =  ["name" => '', "icon" => '', "active" => true, "location" => 'Test', "date" => 'DD.MM.YY HH', 
"uts" => mktime(17, 59, 0, 12, 31, 2020), "link" => "subDir/", "path" => "{$fp}/filename.csv"];
name: Name of the event (string)
icon: Icon of the tile (string), see css/style.css for list of all icons (example: beer)
location: Location of the event (string)
date: Date and Time of the event (string) in a human readable format
uts: Unix Timestamp of the event (int), or mktime(hour, minute, second, MONTH, DAY, year)  (ATTENTION! American Time Format)
     The cookie, that will be set after a registration, will expire at this time +24h
     After reaching this timestamp no further registration will be possible  
link: Link to the event (string), relativ path to sub-directory
path: Relativ path to CSV file (string), only necessary if used 

