<?php

namespace HafasClient\Exception;

use Exception;

class ProductNotFoundException extends Exception {

    private string $product;

    public function __construct(string $product) {
        $this->product = $product;
        parent::__construct(strtr('Product :product not found', [
            ':product' => $product
        ]));
    }

    public function getProduct(): string {
        return $this->product;
    }
}