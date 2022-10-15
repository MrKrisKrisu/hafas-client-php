<?php

namespace HafasClient\Response;

use stdClass;
use HafasClient\Helper\Time;
use HafasClient\Models\Location;
use HafasClient\Models\Stop;
use HafasClient\Models\Operator;

class JourneyMatchResponse
{

    private stdClass $raw;

    public function __construct(stdClass $raw)
    {
        $this->raw = $raw;
    }

    public function parse(): array
    {
        $journeys = [];
        foreach ($this->raw->svcResL[0]->res->jnyL as $rawJourney) {
            $rawOrigin = $this->raw->svcResL[0]->res->common->locL[$rawJourney->stopL[0]->locX];
            $rawDestination = $this->raw->svcResL[0]->res->common->locL[$rawJourney->stopL[count($rawJourney->stopL) - 1]->locX];
            $rawProd = $this->raw->svcResL[0]->res->common->prodL[$rawJourney->prodX];
            $rawOperator = $this->raw->svcResL[0]->res->common->opL[$rawProd->oprX];

            if (isset($rawOperator)) {
                $operator = new Operator(
                    id: null,
                    name: $rawOperator?->name ?? null,
                );
            }

            $journey = [
                'type' => 'journey',
                'id' => $rawJourney->jid,
                'name' => $rawProd?->name,
                'line' => $rawProd?->prodCtx?->line,
                'origin' => new Stop(
                    id: $rawOrigin?->extId,
                    name: $rawOrigin?->name,
                    location: new Location(
                        latitude: $rawOrigin?->crd?->y / 1000000,
                        longitude: $rawOrigin?->crd?->x / 1000000,
                        altitude: $rawOrigin?->crd?->z ?? null
                    )
                ),
                'departure_planned' => Time::parseDatetime($rawJourney->date, $rawJourney->stopL[0]->dTimeS),
                'destination' => new Stop(
                    id: $rawDestination?->extId,
                    name: $rawDestination?->name,
                    location: new Location(
                        latitude: $rawDestination?->crd?->y / 1000000,
                        longitude: $rawDestination?->crd?->x / 1000000,
                        altitude: $rawDestination?->crd?->z ?? null
                    )
                ),
                'arrival_planned' => Time::parseDatetime($rawJourney->date, $rawJourney->stopL[1]->aTimeS),
                'operator' => $operator ?? null,
            ];

            $journeys[] = $journey;
        }
        return $journeys;
    }
}