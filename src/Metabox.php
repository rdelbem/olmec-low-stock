<?php

namespace Olmec\LowStock;

if (!defined('ABSPATH')) {
    exit;
}

use Olmec\LowStock\Stock;
use Olmec\LowStock\Utils\LoadTemplate;

final class Metabox
{
    use LoadTemplate;
    private Stock $stock;

    public function __construct() {
        $this->stock = new Stock();
    }
    public function register(): void {
        wp_add_dashboard_widget(
            'olmec_stock_dashboard_widget',
            __('Stock Information by Category', OLMEC_LOW_STOCK_TEXT_DOMAIN),
            fn () => $this->widget()
        );
    }

    public function widget(): void {
        $stocks = $this->stock->getStockInfos();
        $this->render('metabox', ['stocks' => $stocks]);
    }
}
