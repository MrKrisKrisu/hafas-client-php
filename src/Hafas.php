<?php

namespace HafasClient;

use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use HafasClient\Response\StationBoardResponse;
use HafasClient\Response\LocMatchResponse;
use HafasClient\Response\JourneyDetailsResponse;
use HafasClient\Models\Journey;
use HafasClient\Response\LocGeoPosResponse;
use HafasClient\Helper\ProductFilter;

abstract class Hafas {

    /**
     * @throws GuzzleException|Exception\InvalidHafasResponse
     * @throws Exception\ProductNotFoundException
     * @todo parse stopovers
     * @todo set language in request
     * @todo support remarks, hints, warnings
     * @todo filter by direction
     */
    public static function getDepartures(
        int $lid,
        Carbon $timestamp,
        int $maxJourneys = 5,
        int $duration = -1,
        ProductFilter $filter = null,
    ): ?array {
        if($filter == null) {
            //true is default for all
            $filter = new ProductFilter();
        }

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
                'jnyFltrL' => [$filter->filter()]
            ],
            'meth' => 'StationBoard'
        ];

        $response = new StationBoardResponse(Request::request($data));
        return $response->parse();
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
    ): ?array {
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

        $response = new StationBoardResponse(Request::request($data));
        return $response->parse();
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
    ): ?array {
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

        $response = new LocMatchResponse(Request::request($data));
        return $response->parse();
    }

    /**
     * @throws GuzzleException
     * @throws Exception\InvalidHafasResponse
     */
    public static function getJourney(string $journeyId): ?Journey {
        $data     = [
            'req'  => [
                'jid' => $journeyId
            ],
            'meth' => 'JourneyDetails'
        ];
        $response = new JourneyDetailsResponse(Request::request($data));
        return $response->parse();
    }

    /**
     * @throws GuzzleException
     * @throws Exception\InvalidHafasResponse
     */
    public static function getNearby(float $latitude, float $longitude, $limit = 8): array {
        $data = [
            'req'  => [
                "ring"     => [
                    "cCrd"    => [
                        "x" => $longitude * 1000000,
                        "y" => $latitude * 1000000
                    ],
                    "maxDist" => -1,
                    "minDist" => 0
                ],
                "locFltrL" => [
                    [
                        "type"  => "PROD",
                        "mode"  => "INC",
                        "value" => "1023"
                    ]
                ],
                "getPOIs"  => false,
                "getStops" => true,
                "maxLoc"   => $limit
            ],
            'cfg'  => [
                'polyEnc' => 'GPA',
                'rtMode'  => 'HYBRID',
            ],
            'meth' => 'LocGeoPos'
        ];

        $response = new LocGeoPosResponse(Request::request($data));
        return $response->parse();
    }

}