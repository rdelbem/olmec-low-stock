<?php

namespace Olmec\LowStock;

use Olmec\LowStock\Notification;

class Stock
{
    public Notification $notification;

    public function __construct()
    {
        $this->notification = new Notification();
    }

    public function checkStockLevels(\WC_Order $order)
    {
        $categoryStock = get_option('olmec_categories_stock', []);

        foreach ($order->get_items() as $item) {
            $product = $item->get_product();
            $categories = get_the_terms($product->get_id(), 'product_cat');

            foreach ($categories as $category) {
                if (!isset($categoryStock[$category->term_id])) {
                    continue;
                }

                $categoryStock[$category->term_id] -= $item->get_quantity();

                if ($categoryStock[$category->term_id] < 15) {
                    $this->notification->enqueueNotification($category->term_id, $category->name);
                }
            }
        }

        update_option('olmec_categories_stock', $categoryStock);
    }

    public function updateCategoryIndexOnSave(int $postId, $post = null, $update = null): void
    {
        if (get_post_type($postId) !== 'product') {
            return;
        }

        $product = wc_get_product($postId);
        $categories = get_the_terms($postId, 'product_cat');
        $categoryStock = get_option('olmec_categories_stock', []);

        foreach ($categories as $category) {
            if (isset($categoryStock[$category->term_id])) {
                $categoryStock[$category->term_id] += $product->get_stock_quantity();
            } else {
                $categoryStock[$category->term_id] = $product->get_stock_quantity();
            }
        }

        update_option('olmec_categories_stock', $categoryStock);
    }

    public function getStockInfos()
    {
        $indexedCategories = get_option('olmec_categories_stock', []);
        $categoriesNamesAndStocks = [];
        foreach ($indexedCategories as $category => $stock) {
            $categoriesNamesAndStocks[] = ['name' => (get_term_by('id', $category, 'product_cat'))->name, 'quantity' => $stock];
        }

        return $categoriesNamesAndStocks;
    }
}
