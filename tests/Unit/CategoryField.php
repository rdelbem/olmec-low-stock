<?php

use Olmec\LowStock\CategoryField;

function is_admin() {
    global $mockIsAdmin;
    return $mockIsAdmin ? $mockIsAdmin->is_admin() : false;
}

function get_term_meta($term_id, $key, $single) {
    global $mockGetTermMeta;
    return $mockGetTermMeta ? $mockGetTermMeta->get_term_meta($term_id, $key, $single) : null;
}

function update_term_meta($term_id, $key, $value) {
    global $mockUpdateTermMeta;
    return $mockUpdateTermMeta ? $mockUpdateTermMeta->update_term_meta($term_id, $key, $value) : false;
}

beforeEach(function () {
    global $mockIsAdmin, $mockGetTermMeta, $mockUpdateTermMeta;
    
    $mockIsAdmin = Mockery::mock();
    $mockGetTermMeta = Mockery::mock();
    $mockUpdateTermMeta = Mockery::mock();
});

afterEach(function () {
    Mockery::close();
});

test('addCategoryFields only executes in admin', function () {
    global $mockIsAdmin;
    $mockIsAdmin->shouldReceive('is_admin')->andReturn(false);
    
    $categoryField = new CategoryField();
    $result = $categoryField->addCategoryFields();
    expect($result)->toBeNull();
});

test('addCategoryFields calls render method correctly', function () {
    global $mockIsAdmin;
    $mockIsAdmin->shouldReceive('is_admin')->andReturn(true);

    $categoryField = Mockery::mock('Olmec\LowStock\CategoryField[render]');
    $categoryField->shouldReceive('render')->once()->with('category-field');
    
    $categoryField->addCategoryFields();
});

test('editCategoryFields calls render with correct parameters', function () {
    global $mockIsAdmin, $mockGetTermMeta;
    $mockIsAdmin->shouldReceive('is_admin')->andReturn(true);
    $mockGetTermMeta->shouldReceive('get_term_meta')->with(123, 'cat_low_stock_notification', true)->andReturn('yes');

    $categoryField = Mockery::mock('Olmec\LowStock\CategoryField[render]');
    $categoryField->shouldReceive('render')->once()->with('table-row-category-field', ['checked' => 'yes']);

    $term = (object) ['term_id' => 123];
    $categoryField->editCategoryFields($term);
});

test('saveCategoryFields updates term meta based on POST data', function () {
    global $mockUpdateTermMeta;
    $_POST['cat_low_stock_notification'] = '1';
    $mockUpdateTermMeta->shouldReceive('update_term_meta')->once()->with(123, 'cat_low_stock_notification', 1)->andReturn(true);

    $categoryField = new CategoryField();
    $categoryField->saveCategoryFields(123);
    unset($_POST['cat_low_stock_notification']);
});