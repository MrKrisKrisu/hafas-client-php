<?php

require_once '../vendor/autoload.php';

$data = \HafasClient\Hafas::searchTrips('ICE 70');
print_r($data);
