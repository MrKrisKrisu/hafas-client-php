<?php

namespace HafasClient\Response;

use stdClass;
use HafasClient\Exception\InvalidHafasResponse;
use HafasClient\Models\Stop;
use HafasClient\Models\Location;

class LocGeoPosResponse {

    private stdClass $rawResponse;

    /**
     * @throws InvalidHafasResponse
     */
    public function __construct(stdClass $rawResponse) {
        $this->rawResponse = $rawResponse;
        if(!isset($rawResponse->svcResL[0]->res->locL)) {
            throw new InvalidHafasResponse();
        }
    }

    public function parse(): array {
        $data = [];

        foreach($this->rawResponse->svcResL[0]->res->locL as $location) {
            $stop           = new Stop(
                id: $location->extId,
                name: $location->name,
                location: new Location(
                        latitude: $location->crd->y / 1000000,
                        longitude: $location->crd->x / 1000000,
                        altitude: $location->crd->z ?? 0
                    )
            );
            $stop->distance = $location?->dist;
            $data[]         = $stop;
        }

        return $data;
    }
}