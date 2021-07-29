<?php

namespace HafasClient\Helper;

use HafasClient\Exception\ProductNotFoundException;
use HafasClient\Exception\InvalidFilterException;

/**
 * Class ProductFilter
 * @package HafasClient\Helper
 * @todo    wtf is this class messy, should be cleaned up later™️
 */
class ProductFilter {

    /**
     * @var array
     * @see https://github.com/public-transport/hafas-client/blob/master/p/db/products.js
     */
    private static array $products = [
        'nationalExpress' => [
            'id'       => 'nationalExpress',
            'mode'     => 'train',
            'bitmasks' => [1],
            'name'     => 'InterCityExpress',
            'short'    => 'ICE',
            'default'  => true
        ],
        'national'        => [
            'id'       => 'national',
            'mode'     => 'train',
            'bitmasks' => [2],
            'name'     => 'InterCity & EuroCity',
            'short'    => 'IC/EC',
            'default'  => true
        ],
        'regionalExp'     => [
            'id'       => 'regionalExp',
            'mode'     => 'train',
            'bitmasks' => [4],
            'name'     => 'RegionalExpress & InterRegio',
            'short'    => 'RE/IR',
            'default'  => true
        ],
        'regional'        => [
            'id'       => 'regional',
            'mode'     => 'train',
            'bitmasks' => [8],
            'name'     => 'Regio',
            'short'    => 'RB',
            'default'  => true
        ],
        'suburban'        => [
            'id'       => 'suburban',
            'mode'     => 'train',
            'bitmasks' => [16],
            'name'     => 'S-Bahn',
            'short'    => 'S',
            'default'  => true
        ],
        'bus'             => [
            'id'       => 'bus',
            'mode'     => 'bus',
            'bitmasks' => [32],
            'name'     => 'Bus',
            'short'    => 'B',
            'default'  => true
        ],
        'ferry'           => [
            'id'       => 'ferry',
            'mode'     => 'watercraft',
            'bitmasks' => [64],
            'name'     => 'Ferry',
            'short'    => 'F',
            'default'  => true
        ],
        'subway'          => [
            'id'       => 'subway',
            'mode'     => 'train',
            'bitmasks' => [128],
            'name'     => 'U-Bahn',
            'short'    => 'U',
            'default'  => true
        ],
        'tram'            => [
            'id'       => 'tram',
            'mode'     => 'train',
            'bitmasks' => [256],
            'name'     => 'Tram',
            'short'    => 'T',
            'default'  => true
        ],
        'taxi'            => [
            'id'       => 'taxi',
            'mode'     => 'taxi',
            'bitmasks' => [512],
            'name'     => 'Group Taxi',
            'short'    => 'Taxi',
            'default'  => true
        ]
    ];

    public static function getProducts(): array {
        return self::$products;
    }

    /**
     * @param array $products
     *
     * @return int
     * @throws ProductNotFoundException
     * @throws InvalidFilterException
     */
    public static function createBitmask(array $products): int {
        if(count($products) == 0) {
            throw new InvalidFilterException();
        }
        $bitmask = 0;
        foreach($products as $product) {
            if(!array_key_exists($product, self::$products)) {
                throw new ProductNotFoundException($product);
            }
            $bitmask += self::$products[$product]['bitmasks'][0];
        }
        return $bitmask;
    }

    private $filter = [];

    /**
     * @todo    wtf is this messy, should be cleaned up later™️
     */
    public function __construct(
        bool $nationalExpress = true,
        bool $national = true,
        bool $regionalExp = true,
        bool $regional = true,
        bool $suburban = true,
        bool $bus = true,
        bool $ferry = true,
        bool $subway = true,
        bool $tram = true,
        bool $taxi = true,
    ) {
        if($nationalExpress) {
            $this->filter[] = 'nationalExpress';
        }
        if($national) {
            $this->filter[] = 'national';
        }
        if($regionalExp) {
            $this->filter[] = 'regionalExp';
        }
        if($regional) {
            $this->filter[] = 'regional';
        }
        if($suburban) {
            $this->filter[] = 'suburban';
        }
        if($bus) {
            $this->filter[] = 'bus';
        }
        if($ferry) {
            $this->filter[] = 'ferry';
        }
        if($subway) {
            $this->filter[] = 'subway';
        }
        if($tram) {
            $this->filter[] = 'tram';
        }
        if($taxi) {
            $this->filter[] = 'taxi';
        }
    }

    /**
     * @return array
     * @throws ProductNotFoundException|InvalidFilterException
     */
    public function filter(): array {
        return [
            'type'  => 'PROD',
            'mode'  => 'INC',
            'value' => self::createBitmask($this->filter)
        ];
    }

}