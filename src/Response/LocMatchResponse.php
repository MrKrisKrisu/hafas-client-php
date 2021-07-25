<?php

namespace HafasClient\Response;

use stdClass;
use HafasClient\Exception\InvalidHafasResponse;
use HafasClient\Models\Location;
use HafasClient\Models\Stop;

class LocMatchResponse {

    private stdClass $rawResponse;

    /**
     * @throws InvalidHafasResponse
     */
    public function __construct(stdClass $rawResponse) {
        $this->rawResponse = $rawResponse;
        if(!isset($rawResponse->svcResL[0]->res->match->locL)) {
            throw new InvalidHafasResponse();
        }
    }

    public function parse(): array {
        $locations = [];

        foreach($this->rawResponse->svcResL[0]->res->match->locL as $rawLocation) {
            $location = new Stop(
                id: $rawLocation->extId,
                name: $rawLocation->name,
                location: new Location(
                        latitude: $rawLocation?->crd?->y / 1000000,
                        longitude: $rawLocation?->crd?->x / 1000000,
                        altitude: $rawLocation?->crd?->z ?? null
                    )
            );

            $locations[] = $location;
        }

        return $locations;
    }
}