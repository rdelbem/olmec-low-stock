<?php

use Tests\ProductMock;

function get_terms($args) {
    return [
        (object) ['term_id' => 1, 'name' => 'Category 1'],
        (object) ['term_id' => 2, 'name' => 'Category 2']
    ];
}

function wc_get_products($args) {
    if (in_array(1, $args['category'])) {
        return [
            new ProductMock(10), 
            new ProductMock(5)  
        ];
    } elseif (in_array(2, $args['category'])) {
        return [
            new ProductMock(3) 
        ];
    }
    return [];
}

function update_option($option, $value) {
    global $mock;
    $mock->update_option($option, $value);
}

test('Activation::run should correctly index stock quantities by category', function () {
    global $mock;
    $mock = \Mockery::mock();
    $mock->shouldReceive('update_option')
         ->once()
         ->with('olmec_categories_stock', ['1' => 15, '2' => 3]);

    \Olmec\LowStock\Activation::run();
});

afterEach(function () {
    \Mockery::close();
});
