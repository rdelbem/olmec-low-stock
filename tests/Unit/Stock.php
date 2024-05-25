<?php

use Olmec\LowStock\Stock;
use Olmec\LowStock\Notification;


function get_the_terms($post_id, $taxonomy) {
    global $mockGetTheTerms;
    return $mockGetTheTerms ? $mockGetTheTerms->get_the_terms($post_id, $taxonomy) : [];
}

function wc_get_product($product_id) {
    global $mockWcGetProduct;
    return $mockWcGetProduct ? $mockWcGetProduct->wc_get_product($product_id) : null;
}

function get_post_type($post_id) {
    global $mockGetPostType;
    return $mockGetPostType ? $mockGetPostType->get_post_type($post_id) : null;
}

function get_term_by($field, $value, $taxonomy) {
    global $mockGetTermBy;
    return $mockGetTermBy ? $mockGetTermBy->get_term_by($field, $value, $taxonomy) : null;
}

beforeEach(function () {
    global $mockGetOption, $mockUpdateOption, $mockGetTheTerms, $mockWcGetProduct, $mockGetPostType, $mockGetTermBy;
    $mockGetOption = Mockery::mock();
    $mockUpdateOption = Mockery::mock();
    $mockGetTheTerms = Mockery::mock();
    $mockWcGetProduct = Mockery::mock();
    $mockGetPostType = Mockery::mock();
    $mockGetTermBy = Mockery::mock();
});

afterEach(function () {
    Mockery::close();
});

test('checkStockLevels should enqueue notification if stock is low', function () {
    global $mockGetOption, $mockUpdateOption, $mockGetTheTerms;

    $mockGetOption
        ->shouldReceive('get_option')
        ->with('olmec_categories_stock', [])
        ->andReturn(['1' => 20]);

    $product = Mockery::mock();
    $product->shouldReceive('get_id')->andReturn(1);
    $product->shouldReceive('get_stock_quantity')->andReturn(10);

    $item = Mockery::mock();
    $item->shouldReceive('get_product')->andReturn($product);
    $item->shouldReceive('get_quantity')->andReturn(10);

    $order = Mockery::mock(\WC_Order::class);
    $order->shouldReceive('get_items')->andReturn([$item]);

    $mockGetTheTerms
        ->shouldReceive('get_the_terms')
        ->with(1, 'product_cat')
        ->andReturn([(object)['term_id' => 1, 'name' => 'Category 1']]);

    $mockUpdateOption
        ->shouldReceive('update_option')
        ->once()
        ->with('olmec_categories_stock', ['1' => 10]);

    $notificationMock = Mockery::mock(Notification::class);
    $notificationMock->shouldReceive('enqueueNotification')
                     ->once()
                     ->with(1, 'Category 1');

    $stock = Mockery::mock(Stock::class)->makePartial();
    $stock->notification = $notificationMock;

    $stock->checkStockLevels($order);
});

test('updateCategoryIndexOnSave should update category stock', function () {
    global $mockGetOption, $mockUpdateOption, $mockGetTheTerms, $mockWcGetProduct, $mockGetPostType;

    $mockGetPostType
        ->shouldReceive('get_post_type')
        ->with(1)
        ->andReturn('product');

    $product = Mockery::mock();
    $product->shouldReceive('get_stock_quantity')->andReturn(10);

    $mockWcGetProduct
        ->shouldReceive('wc_get_product')
        ->with(1)
        ->andReturn($product);

    $mockGetTheTerms
        ->shouldReceive('get_the_terms')
        ->with(1, 'product_cat')
        ->andReturn([(object)['term_id' => 1, 'name' => 'Category 1']]);

    $mockGetOption
        ->shouldReceive('get_option')
        ->with('olmec_categories_stock', [])
        ->andReturn(['1' => 20]);

    $mockUpdateOption
        ->shouldReceive('update_option')
        ->once()
        ->with('olmec_categories_stock', ['1' => 30]);

    $stock = new Stock();
    $stock->updateCategoryIndexOnSave(1);
});

test('getStockInfos should return category names and stock quantities', function () {
    global $mockGetOption, $mockGetTermBy;

    $mockGetOption
        ->shouldReceive('get_option')
        ->with('olmec_categories_stock', [])
        ->andReturn(['1' => 20]);

    $mockGetTermBy
        ->shouldReceive('get_term_by')
        ->with('id', 1, 'product_cat')
        ->andReturn((object)['name' => 'Category 1']);

    $stock = new Stock();
    $result = $stock->getStockInfos();

    expect($result)->toBeArray();
    expect($result)->toContain(['name' => 'Category 1', 'quantity' => 20]);
});