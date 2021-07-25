<?php

namespace HafasClient\Models;

use Carbon\Carbon;

/**
 * @package HafasClient\Models
 * @todo    make readonly
 */
class Journey {

    public string  $journeyId;
    public ?string $direction;
    public ?Carbon $date;
    public ?Line   $line;
    public ?array  $stopovers;

    public function __construct(
        string $journeyId,
        string $direction = null,
        Carbon $date = null,
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
                               'date'      => $this->date?->format('Y-m-d'),
                               'line'      => isset($this->line) ? (string)$this->line : null,
                               'stopovers' => $this->stopovers,
                           ]);
    }
}