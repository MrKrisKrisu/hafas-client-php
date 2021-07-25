<?php

namespace HafasClient\Response;

use stdClass;
use HafasClient\Exception\InvalidHafasResponse;
use HafasClient\Models\Journey;
use HafasClient\Models\Line;
use HafasClient\Models\Operator;
use HafasClient\Models\Stopover;
use HafasClient\Models\Stop;
use HafasClient\Models\Location;
use HafasClient\Helper\Time;
use HafasClient\Models\Remark;

class JourneyDetailsResponse {

    private stdClass $rawResponse;

    /**
     * @throws InvalidHafasResponse
     */
    public function __construct(stdClass $rawResponse) {
        $this->rawResponse = $rawResponse;
        if(!isset($rawResponse->svcResL[0]->res->journey)) {
            throw new InvalidHafasResponse();
        }
    }

    public function parse(): Journey {
        $rawJourney      = $this->rawResponse->svcResL[0]->res->journey;
        $rawCommon       = $this->rawResponse->svcResL[0]->res->common;
        $rawLine         = $rawCommon->prodL[$rawJourney->prodX];
        $rawLineOperator = $rawCommon->opL[$rawLine->oprX];

        $stopovers = [];
        foreach($rawJourney->stopL as $rawStop) {
            $rawLoc      = $rawCommon->locL[$rawStop->locX];
            $stopovers[] = new Stopover(
                stop: new Stop(
                          id: $rawLoc?->extId,
                          name: $rawLoc?->name,
                          location: new Location(
                                  latitude: $rawLoc?->crd?->y / 1000000,
                                  longitude: $rawLoc?->crd?->x / 1000000,
                                  altitude: $rawLoc?->crd?->z ?? null
                              )
                      ),
                index: $rawStop?->idx,
                plannedArrival: isset($rawStop->aTimeS) ? Time::parseDatetime($rawJourney->date, $rawStop->aTimeS) : null,
                predictedArrival: isset($rawStop->aTimeR) ? Time::parseDatetime($rawJourney->date, $rawStop->aTimeR) : null,
                arrivalPlatform: $rawStop?->aPlatfS ?? null,
                plannedDeparture: isset($rawStop->dTimeS) ? Time::parseDatetime($rawJourney->date, $rawStop->dTimeS) : null,
                predictedDeparture: isset($rawStop->dTimeR) ? Time::parseDatetime($rawJourney->date, $rawStop->dTimeR) : null,
                departurePlatform: $rawStop?->dPlatfS ?? null,
                isCancelled: $rawStop?->aCncl || $rawStop?->dCnl,
            );
        }

        $remarks = [];
        foreach($rawJourney->msgL as $message) {
            $rawMessage = $rawCommon->remL[$message->remX];

            $remarks[] = new Remark(
                type: $rawMessage?->type,
                code: $rawMessage?->code,
                prio: $rawMessage?->prio,
                message: $rawMessage?->txtN,
            );
        }

        return new Journey(
            journeyId: $rawJourney?->jid,
            direction: $rawJourney?->dirTxt,
            date: Time::parseDate($rawJourney->date),
            line: new Line(
                           id: '???', //TODO
                           name: $rawLine?->name,
                           category: $rawLine?->prodCtx?->catOut,
                           number: $rawLine?->number,
                           mode: '???',   //TODO
                           product: '???',//TODO
                           operator: new Operator(
                                   id: $rawLineOperator?->name, //TODO: where from?
                                   name: $rawLineOperator?->name
                               )
                       ),
            stopovers: $stopovers,
            remarks: $remarks,
        );
    }
}