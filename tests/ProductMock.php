<?php

namespace Tests;

/**
 * Stub class
 */
class ProductMock {
    private $stock_quantity;

    public function __construct($stock_quantity) {
        $this->stock_quantity = $stock_quantity;
    }

    public function get_stock_quantity() {
        return $this->stock_quantity;
    }
}