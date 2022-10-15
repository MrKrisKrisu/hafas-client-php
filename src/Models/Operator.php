<?php

namespace HafasClient\Models;

/**
 * @package HafasClient\Models
 * @todo    make readonly
 */
class Operator {

    public ?string  $id;
    public ?string $name;

    public function __construct(string $id = null, string $name = null) {
        $this->id   = $id;
        $this->name = $name;
    }

    public function __toString(): string {
        return json_encode([
                               'type' => 'operator',
                               'id'   => $this->id,
                               'name' => $this->name,
                           ]);
    }
}