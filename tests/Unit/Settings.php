<?php

use Olmec\LowStock\Settings;

function woocommerce_admin_fields($fields) {
    global $mockWoocommerceAdminFields;
    return $mockWoocommerceAdminFields ? $mockWoocommerceAdminFields->woocommerce_admin_fields($fields) : true;
}

function woocommerce_update_options($fields) {
    global $mockWoocommerceUpdateOptions;
    return $mockWoocommerceUpdateOptions ? $mockWoocommerceUpdateOptions->woocommerce_update_options($fields) : true;
}

beforeEach(function () {
    global $mock__, $mockWoocommerceAdminFields, $mockWoocommerceUpdateOptions;
    $mock__ = Mockery::mock();
    $mockWoocommerceAdminFields = Mockery::mock();
    $mockWoocommerceUpdateOptions = Mockery::mock();
});

afterEach(function () {
    Mockery::close();
});

test('addSettingsTab should add a new settings tab', function () {
    global $mock__;

    $mock__
        ->shouldReceive('__')
        ->with('Low stock by category', 'olmec-low-stock-text-domain')
        ->andReturn('Low stock by category');

    $settings = new Settings();
    $settingsTab = [];
    $result = $settings->addSettingsTab($settingsTab);

    expect($result)->toHaveKey('olmec_lowstock_options');
    expect($result['olmec_lowstock_options'])->toBe('Low stock by category');
});

test('addSettings should display settings fields', function () {
    global $mockWoocommerceAdminFields;

    $mockWoocommerceAdminFields
        ->shouldReceive('woocommerce_admin_fields')
        ->once()
        ->with(Mockery::type('array'));

    $settings = new Settings();
    $settings->addSettings();
});

test('getSettings should return an array of settings', function () {
    global $mock__;

    $mock__
        ->shouldReceive('__')
        ->with('Low Stock by Category Options', 'olmec-low-stock-text-domain')
        ->andReturn('Low Stock by Category Options');
    
    $mock__
        ->shouldReceive('__')
        ->with('Low Stock by category will notify you when an entire category reaches 15 unities', 'olmec-low-stock-text-domain')
        ->andReturn('Low Stock by category will notify you when an entire category reaches 15 unities');

    $mock__
        ->shouldReceive('__')
        ->with('Show metabox on dashboard', 'olmec-low-stock-text-domain')
        ->andReturn('Show metabox on dashboard');

    $mock__
        ->shouldReceive('__')
        ->with('Send email notification to store manager', 'olmec-low-stock-text-domain')
        ->andReturn('Send email notification to store manager');

    $settings = new Settings();
    $result = $settings->getSettings();

    expect($result)->toBeArray();
    expect($result)->toHaveKey('section_title');
    expect($result['section_title']['name'])->toBe('Low Stock by Category Options');
    expect($result['section_title']['desc'])->toBe('Low Stock by category will notify you when an entire category reaches 15 unities');
    expect($result)->toHaveKey('olmec_option_metabox_setting');
    expect($result['olmec_option_metabox_setting']['name'])->toBe('Show metabox on dashboard');
    expect($result)->toHaveKey('olmec_option_notification_setting');
    expect($result['olmec_option_notification_setting']['name'])->toBe('Send email notification to store manager');
});

test('saveSettings should update settings', function () {
    global $mockWoocommerceUpdateOptions;

    $mockWoocommerceUpdateOptions
        ->shouldReceive('woocommerce_update_options')
        ->once()
        ->with(Mockery::type('array'));

    $settings = new Settings();
    $settings->saveSettings();
});