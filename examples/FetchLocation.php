<?php

require_once '../vendor/autoload.php';

$data = \HafasClient\Hafas::getLocation("Hannover Hbf");
print_r($data->parse());
