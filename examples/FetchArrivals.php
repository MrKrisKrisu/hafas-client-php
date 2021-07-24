<?php

require_once '../vendor/autoload.php';

$data = \HafasClient\Hafas::getArrivals(8000152, \Carbon\Carbon::now(), 5);
print_r($data);