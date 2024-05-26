<?php

namespace Olmec\LowStock;

final class Activation
{
    public static function run(): void
    {
        self::starStockIndexation();
    }

    public static function starStockIndexation(): void
    {
        $categories = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
        ]);

        $categoryStock = [];

        foreach ($categories as $category) {
            $args = [
                'status' => 'publish',
                'limit' => -1,
                'category' => [$category->term_id]
            ];

            $products = wc_get_products($args);
            $totalStock = 0;

            foreach ($products as $product) {
                $totalStock += $product->get_stock_quantity();
            }

            $categoryStock[$category->term_id] = $totalStock;
        }

        update_option('olmec_categories_stock', $categoryStock);
    }
}
