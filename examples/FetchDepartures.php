<?php

use HafasClient\Helper\ProductFilter;

require_once '../vendor/autoload.php';

$data = \HafasClient\Hafas::getDepartures(
    lid: 8000152,
    timestamp: \Carbon\Carbon::now(),
    maxJourneys: 2,
    filter: new ProductFilter(
             nationalExpress: true,
             national: true,
             regionalExp: true,
             regional: true,
             suburban: false,
             bus: false,
             ferry: false,
             subway: false,
             tram: false,
             taxi: false,
         )
);

if(!isset($data[0])) {
    echo "There is no departure for the given stop in the given time." . PHP_EOL;
    return;
}

echo strtr('The next departure is direction :direction.' . PHP_EOL, [
    ':direction' => $data[0]['direction']
]);
