<?php

require_once '../vendor/autoload.php';

$data = \HafasClient\Hafas::getArrivals();
print_r($data);