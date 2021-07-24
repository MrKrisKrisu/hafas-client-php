<?php

require_once '../vendor/autoload.php';

$data = \HafasClient\Hafas::getDepartures();
print_r($data);