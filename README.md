# eei - Erst Einführungs Interface

## Testing
* Requires `php` to be installed
* Run a local test instance using `php -S localhost:8000`

## Adding an event:
### config.php
To add an event copy the dummy event and modify the values. Then, append this event to the $events array
The oder in this array defines the shown order

Example:
```php
$SA = ["name" => 'Spieleabend', "icon" => 'cap', "active" => true, "location" => 'Sand 14 - A301', "date" => '16.04.20', 
       "uts" => mktime(18, 0, 0, 04, 16, 2020), "link" => 'spa-akad/', "path" => "{$fp}anfi-sp-akad.csv"];
```
Whereas: 
- name: Name of the event (string)
- icon: Icon of the tile (string), see css/style.css for list of all icons (example: beer)
- location: Location of the event (string)
- date: Date and Time of the event (string) in a human readable format
- uts: Unix Timestamp of the event (int), or mktime(hour, minute, second, MONTH, DAY, year)  (ATTENTION! American Time Format)
   - The cookie, that will be set after a registration, will expire at this time +24h
   - After reaching this timestamp no further registration will be possible  
- link: Link to the event (string), relativ path to sub-directory
- path: Relativ path to CSV file (string), only necessary if used 

After that add this event to the events array:

Example:
```php
$events = ['RY' => $RY, 'KT' => $KT, 'FR' => $FR, 'SA' => $SA];
```

### Folder
If it's desired to link a direkt overview page (with or without registration) of this event create a folder and copy the dummy/index.php into it.
Don't forget to set the correct event key `$short = 'SA'; #Kürzel des Events` as it was set in the $events array.

Now edit the (text) content as will
