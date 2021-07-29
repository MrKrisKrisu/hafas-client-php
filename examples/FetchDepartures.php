<?php

require_once '../vendor/autoload.php';

$data = \HafasClient\Hafas::getDepartures(8000152, \Carbon\Carbon::now(), 2);

if(!isset($data[0])) {
    echo "There is no departure for the given stop in the given time." . PHP_EOL;
    return;
}

echo strtr('The next departure is direction :direction.' . PHP_EOL, [
    ':direction' => $data[0]['direction']
]);
