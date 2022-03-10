<?php

namespace HafasClient\Response;

use stdClass;
use HafasClient\Exception\InvalidHafasResponse;
use HafasClient\Helper\Time;
use HafasClient\Models\Location;
use HafasClient\Models\Stop;
use HafasClient\Models\Stopover;
use HafasClient\Models\Line;
use HafasClient\Models\Operator;

class StationBoardResponse {

    private stdClass $rawStationBoard;

    /**
     * @param stdClass $rawStationBoard
     *
     * @throws InvalidHafasResponse
     */
    public function __construct(stdClass $rawStationBoard) {
        $this->rawStationBoard = $rawStationBoard;
        if(!isset($rawStationBoard->svcResL[0]->res->jnyL)) {
            throw new InvalidHafasResponse();
        }
        $this->map();
    }

    private function map() {
        for($journeyIndex = 0; $journeyIndex < count($this->rawStationBoard->svcResL[0]->res->jnyL); $journeyIndex++) {
            //stbStop == index 0 of stopList
            unset($this->rawStationBoard->svcResL[0]->res->jnyL[$journeyIndex]->stbStop);

            //map prodX in prodL
            $this->rawStationBoard->svcResL[0]->res->jnyL[$journeyIndex]->prod = $this->rawStationBoard->svcResL[0]->res->common->prodL[$this->rawStationBoard->svcResL[0]->res->jnyL[$journeyIndex]->prodX];
            unset($this->rawStationBoard->svcResL[0]->res->jnyL[$journeyIndex]->prodX);

            $this->rawStationBoard->svcResL[0]->res->jnyL[$journeyIndex]->prod->opr = $this->rawStationBoard->svcResL[0]->res->common->opL[$this->rawStationBoard->svcResL[0]->res->jnyL[$journeyIndex]->prod->oprX];
            unset($this->rawStationBoard->svcResL[0]->res->jnyL[$journeyIndex]->prod->oprX);

            for($stopIndex = 0; $stopIndex < count($this->rawStationBoard->svcResL[0]->res->jnyL[$journeyIndex]->stopL); $stopIndex++) {
                //map locX in stops
                $this->rawStationBoard->svcResL[0]->res->jnyL[$journeyIndex]->stopL[$stopIndex]->loc = $this->rawStationBoard->svcResL[0]->res->common->locL[$this->rawStationBoard->svcResL[0]->res->jnyL[$journeyIndex]->stopL[$stopIndex]->locX];
                unset($this->rawStationBoard->svcResL[0]->res->jnyL[$journeyIndex]->stopL[$stopIndex]->locX);
            }
        }
    }

    public function parse(): array {
        $journeys = [];

        foreach($this->rawStationBoard->svcResL[0]->res->jnyL as $rawJourney) {
            if(isset($rawJourney?->prod?->opr?->name)) {
                $operator = new Operator(
                    id:   $rawJourney?->prod?->opr?->name, //TODO
                    name: $rawJourney?->prod?->opr?->name ?? null,
                );
            }

            $journey = [
                'type'      => 'journey',
                'id'        => $rawJourney->jid,
                'direction' => $rawJourney?->dirTxt ?? null,
                'line'      => new Line(
                    id:       '?', //TODO
                    name:     $rawJourney?->prod?->prodCtx?->name ?? null,
                    category: $rawJourney?->prod?->prodCtx?->catOut ?? null,
                    number:   $rawJourney?->prod?->prodCtx?->line ?? null,
                    mode:     null,    //TODO
                    product:  null,    //TODO
                    operator: $operator ?? null,
                ),
                'stopovers' => [],
            ];

            foreach($rawJourney->stopL as $rawStop) {
                $journey['stopovers'][] = new Stopover(
                    stop:               new Stop(
                                            id:       $rawStop?->loc?->extId,
                                            name:     $rawStop?->loc?->name,
                                            location: new Location(
                                                          latitude:  $rawStop?->loc?->crd?->y / 1000000,
                                                          longitude: $rawStop?->loc?->crd?->x / 1000000,
                                                          altitude:  $rawStop?->loc?->crd?->z ?? null
                                                      )
                                        ),
                    index:              $rawStop?->idx,
                    plannedArrival:     isset($rawStop->aTimeS) ? Time::parseDatetime($rawJourney->date, $rawStop->aTimeS) : null,
                    predictedArrival:   isset($rawStop->aTimeR) ? Time::parseDatetime($rawJourney->date, $rawStop->aTimeR) : null,
                    arrivalPlatform:    $rawStop?->aPlatfS ?? null,
                    plannedDeparture:   isset($rawStop->dTimeS) ? Time::parseDatetime($rawJourney->date, $rawStop->dTimeS) : null,
                    predictedDeparture: isset($rawStop->dTimeR) ? Time::parseDatetime($rawJourney->date, $rawStop->dTimeR) : null,
                    departurePlatform:  $rawStop?->dPlatfS ?? null,
                    isCancelled:        isset($rawStop?->aCncl) || isset($rawStop?->dCnl),
                );
            }

            $journeys[] = $journey;
        }

        return $journeys;
    }
}