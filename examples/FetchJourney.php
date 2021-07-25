<?php

require_once '../vendor/autoload.php';

$tripId = "1|207475|0|80|25072021";
$data   = \HafasClient\Hafas::getJourney($tripId);

$journey = $data->parse();

echo "* You've searched for " . $journey->line->name . ' direction ' . $journey->direction . PHP_EOL;
foreach($journey->stopovers as $stopover) {
    echo "** Station: " . $stopover->stop->name . PHP_EOL;
}
