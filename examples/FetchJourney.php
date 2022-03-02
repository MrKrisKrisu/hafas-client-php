<?php

require_once '../vendor/autoload.php';

$tripId = "1|639068|8|80|2032022";
$journey   = \HafasClient\Hafas::getJourney($tripId);

echo "* You've searched for " . $journey->line->name . ' direction ' . $journey->direction . PHP_EOL;
foreach($journey->stopovers as $stopover) {
    echo "** Station: " . $stopover->stop->name . PHP_EOL;
}
