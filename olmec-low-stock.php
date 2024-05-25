<?php
/**
 * Plugin Name: Olmec Low Stock
 * Plugin URI: https://olmec.dev/plugins/low-stock
 * Description: A WordPress plugin that notifies the store managers about a category running out of stock
 * Version: 1.0.0
 * Author: Olmec, Rodrigo Del Bem
 * Author URI: https://delbem.net/portfolio
 * License: GPL-2.0+
 */

// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once ABSPATH . 'wp-admin/includes/plugin.php';
require_once plugin_dir_path(__FILE__) . '/vendor/autoload.php';

use Olmec\LowStock\CoreLoader;
use Olmec\LowStock\Activation;
use Olmec\LowStock\WPCLI\CustomWPCLI;

// On activation
register_activation_hook(__FILE__, fn() => Activation::run());

if(!class_exists('OlmecLowStockCoreLoader') && is_plugin_active('woocommerce/woocommerce.php')){
    add_action('woocommerce_init', function() {
        new CoreLoader();
    });

    (new CustomWPCLI())->createCommands();
}