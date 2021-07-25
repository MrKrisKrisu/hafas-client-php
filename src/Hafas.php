<?php

namespace HafasClient;

use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use HafasClient\Response\StationBoardResponse;
use HafasClient\Response\LocMatchResponse;

abstract class Hafas {

    /**
     * @throws GuzzleException|Exception\InvalidHafasResponse
     * @todo filter by type (bus, tram, subway, regional, ...)
     * @todo parse stopovers
     * @todo set language in request
     * @todo support remarks, hints, warnings
     * @todo filter by direction
     */
    public static function getDepartures(
        int $lid,
        Carbon $timestamp,
        int $maxJourneys = 5,
        int $duration = -1
    ): StationBoardResponse {
        $data = [
            'req'  => [
                'type'     => 'DEP',
                'stbLoc'   => [
                    'lid' => 'A=1@L=' . $lid . '@',
                ],
                'dirLoc'   => null,
                //[ //direction, not required
                //                'lid' => '',
                //],
                'maxJny'   => $maxJourneys,
                'date'     => $timestamp->format('Ymd'),
                'time'     => $timestamp->format('His'),
                'dur'      => $duration,
                'jnyFltrL' => [
                    [
                        'type'  => 'PROD',
                        'mode'  => 'INC',
                        'value' => '1023'
                    ]
                ]
            ],
            'meth' => 'StationBoard'
        ];

        return new StationBoardResponse(Request::request($data));
    }

    /**
     * @throws GuzzleException|Exception\InvalidHafasResponse
     * @todo filter by type (bus, tram, subway, regional, ...)
     * @todo parse stopovers
     * @todo set language in request
     * @todo support remarks, hints, warnings
     * @todo filter by direction
     */
    public static function getArrivals(
        int $lid,
        Carbon $timestamp,
        int $maxJourneys = 5,
        int $duration = -1
    ): StationBoardResponse {
        $data = [
            'req'  => [
                'type'     => 'ARR',
                'stbLoc'   => [
                    'lid' => 'A=1@L=' . $lid . '@',
                ],
                'dirLoc'   => null,
                //[ //direction, not required
                //                'lid' => '',
                //],
                'maxJny'   => $maxJourneys,
                'date'     => $timestamp->format('Ymd'),
                'time'     => $timestamp->format('His'),
                'dur'      => $duration,
                'jnyFltrL' => [
                    [
                        'type'  => 'PROD',
                        'mode'  => 'INC',
                        'value' => '1023'
                    ]
                ]
            ],
            'meth' => 'StationBoard'
        ];

        return new StationBoardResponse(Request::request($data));
    }

    /**
     * @param string $query
     * @param string $type 'S' = stations, 'ALL' stations and addresses
     *
     * @return LocMatchResponse
     * @throws GuzzleException|Exception\InvalidHafasResponse
     */
    public static function getLocation(
        string $query,
        string $type = 'S'
    ): LocMatchResponse {
        $data = [
            'req'  => [
                'input' => [
                    'field' => 'S',
                    'loc'   => [
                        'name' => $query,
                        'type' => $type
                    ]
                ]
            ],
            'meth' => 'LocMatch'
        ];

        return new LocMatchResponse(Request::request($data));
    }

}