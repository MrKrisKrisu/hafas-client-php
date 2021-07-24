<?php

namespace HafasClient\Models;

use Carbon\Carbon;

/**
 * @package HafasClient\Models
 * @todo    make readonly
 */
class Stopover {

    public Stop    $stop;
    public ?Carbon $plannedArrival;
    public ?Carbon $predictedArrival;
    public ?string $arrivalPlatform;
    public ?Carbon $plannedDeparture;
    public ?Carbon $predictedDeparture;
    public ?string $departurePlatform;

    public function __construct(
        Stop $stop,
        ?Carbon $plannedArrival,
        ?Carbon $predictedArrival,
        ?string $arrivalPlatform,
        ?Carbon $plannedDeparture,
        ?Carbon $predictedDeparture,
        ?string $departurePlatform
    ) {
        $this->stop               = $stop;
        $this->plannedArrival     = $plannedArrival;
        $this->predictedArrival   = $predictedArrival;
        $this->arrivalPlatform    = $arrivalPlatform;
        $this->plannedDeparture   = $plannedDeparture;
        $this->predictedDeparture = $predictedDeparture;
        $this->departurePlatform  = $departurePlatform;
    }
}
