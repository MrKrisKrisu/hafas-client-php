<?php

require_once '../vendor/autoload.php';

$query = "Hannover Hbf";
$data  = \HafasClient\Hafas::getLocation($query);

echo '** You\'ve searched for "' . $query . '" **' . PHP_EOL . PHP_EOL;

foreach($data->parse() as $stop) {
    echo "** Found " . $stop->name . PHP_EOL;
}
