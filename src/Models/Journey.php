<?php

namespace HafasClient\Models;

/**
 * @package HafasClient\Models
 * @todo    make readonly
 */
class Journey {

    public string  $journeyId;
    public ?string $direction;
    public ?Line   $line;
    public ?array  $stopovers;

    public function __construct(
        string $journeyId,
        string $direction = null,
        Line $line = null,
        array $stopovers = null
    ) {
        $this->journeyId = $journeyId;
        $this->direction = $direction;
        $this->line      = $line;
        $this->stopovers = $stopovers;
    }

    public function __toString(): string {
        return json_encode([
                               'type'      => 'journey',
                               'id'        => $this->journeyId,
                               'direction' => $this->direction,
                               'line'      => isset($this->line) ? (string)$this->line : null,
                               'stopovers' => $this->stopovers,
                           ]);
    }
}