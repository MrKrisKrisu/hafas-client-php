<?php

namespace HafasClient\Models;

/**
 * @package HafasClient\Models
 * @todo    make readonly
 */
class Stop {

    public string    $id;
    public string    $name;
    public ?Location $location;

    public function __construct(string $id, string $name, Location $location = null) {
        $this->id       = $id;
        $this->name     = $name;
        $this->location = $location;
    }
}