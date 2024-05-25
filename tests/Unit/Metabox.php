<?php

use Olmec\LowStock\Metabox;
use Olmec\LowStock\Stock;

function __($text, $domain) {
    global $mock__;
    return $mock__ ? $mock__->__($text, $domain) : $text;
}

function wp_add_dashboard_widget($widget_id, $widget_name, $callback) {
    global $mockWpAddDashboardWidget;
    $mockWpAddDashboardWidget->wp_add_dashboard_widget($widget_id, $widget_name, $callback);
}

beforeEach(function () {
    global $mockWpAddDashboardWidget, $mock__;
    $mockWpAddDashboardWidget = Mockery::mock();
    $mock__ = Mockery::mock();
});

afterEach(function () {
    Mockery::close();
});

test('register should add dashboard widget', function () {
    global $mockWpAddDashboardWidget, $mock__;

    $mockWpAddDashboardWidget
        ->shouldReceive('wp_add_dashboard_widget')
        ->once()
        ->with(
            'olmec_stock_dashboard_widget',
            __('Stock Information by Category', OLMEC_LOW_STOCK_TEXT_DOMAIN),
            Mockery::type('callable')
        );

    $metabox = new Metabox();
    $metabox->register();
})->skip();

test('widget should render the correct template with stock information', function () {
    global $mock__;
    $stockMock = Mockery::mock(Stock::class);
    $stockMock
        ->shouldReceive('getStockInfos')
        ->once()
        ->andReturn([
            ['name' => 'Category 1', 'quantity' => 10],
            ['name' => 'Category 2', 'quantity' => 5]
        ]);

    $metabox = Mockery::mock(Metabox::class)->makePartial();
    $metabox->stock = $stockMock;

    $metabox->shouldReceive('render')
            ->once()
            ->with('metabox', ['stocks' => [
                ['name' => 'Category 1', 'quantity' => 10],
                ['name' => 'Category 2', 'quantity' => 5]
            ]]);

    $metabox->widget();
});