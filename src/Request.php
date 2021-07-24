<?php

namespace HafasClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use stdClass;

abstract class Request {

    const SALT = 'bdI8UVj40K5fvxwf';

    /**
     * @param array $data
     *
     * @return stdClass
     * @throws GuzzleException
     */
    public static function request(array $data): stdClass {
        $client = new Client();

        $dummy              = self::getDummyRequestBody();
        $dummy['svcReqL'][] = $data;

        $requestBody = json_encode($dummy);

        $response = $client->post('https://reiseauskunft.bahn.de/bin/mgate.exe?checksum=' . self::getMac($requestBody), [
            'body'    => $requestBody,
            'headers' => [
                'Content-Type' => 'application/json',
                'User-Agent'   => 'DB Navigator/19.10.04 (iPhone; iOS 13.1.2; Scale/2.00)'
            ]

        ]);
        return json_decode($response->getBody()->getContents());
    }

    private static function getMic(string $requestBody): string {
        return md5($requestBody);
    }

    private static function getMac(string $requestBody): string {
        return md5($requestBody . self::SALT);
    }

    private static function getDummyRequestBody(): array {
        return [
            'auth'    => [
                'type' => 'AID',
                'aid'  => 'n91dB8Z77MLdoR0K',
            ],
            'client'  => [
                'id'   => 'DB',
                'v'    => '16040000',
                'type' => 'IPH',
                'name' => 'DB Navigator'
            ],
            'ext'     => 'DB.R19.04.a',
            'svcReqL' => null, //empty request
            'ver'     => '1.16'
        ];
    }
}