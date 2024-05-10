<?php

namespace Olmec\LowStock;

if (!defined('ABSPATH')) {
    exit;
}

use Olmec\LowStock\Stock;
use Olmec\LowStock\Metabox;
use Olmec\LowStock\Notification;
use Olmec\LowStock\ActionHandler;
use Olmec\LowStock\CategoryField;
use Olmec\LowStock\Settings;

final class CoreLoader extends ActionHandler
{
    private Notification $notificationManager;
    private Stock $stockManager;
    private CategoryField $categoryFieldManager;
    private Metabox $metabox;
    private Settings $settings;

    public function __construct()
    {
        $this->notificationManager = new Notification();
        $this->stockManager = new Stock();
        $this->categoryFieldManager = new CategoryField();
        $this->metabox = new Metabox();
        $this->settings = new Settings();

        $this->registerHooks($this->getActionHooks());
        $this->registerFilter($this->getFilterHooks());

        $this->notificationManager->scheduleEvent();
    }

    private function getActionHooks()
    {
        $hooks = [
            'product_cat_add_form_fields' => [
                'instance' => $this->categoryFieldManager,
                'method' => 'addCategoryFields',
                'priority' => 10,
                'acceptedArgs' => 1
            ],
            'product_cat_edit_form_fields' => [
                'instance' => $this->categoryFieldManager,
                'method' => 'editCategoryFields',
                'priority' => 10,
                'acceptedArgs' => 1
            ],
            'edited_product_cat' => [
                'instance' => $this->categoryFieldManager,
                'method' => 'saveCategoryFields',
                'priority' => 10,
                'acceptedArgs' => 1
            ],
            'create_product_cat' => [
                'instance' => $this->categoryFieldManager,
                'method' => 'saveCategoryFields',
                'priority' => 10,
                'acceptedArgs' => 1
            ],
            'save_post' => [
                'instance' => $this->stockManager,
                'method' => 'updateCategoryIndexOnSave',
                'priority' => 10,
                'acceptedArgs' => 3
            ],
            'woocommerce_reduce_order_stock' => [
                'instance' => $this->stockManager,
                'method' => 'checkStockLevels',
            ],
            'olmec_process_notification_queue' => [
                'instance' => $this->notificationManager,
                'method' => 'processNotificationQueue',
            ],
            'woocommerce_settings_olmec_lowstock_options' => [
                'instance' => $this->settings,
                'method' => 'addSettings',
            ],
            'woocommerce_update_options_olmec_lowstock_options' => [
                'instance' => $this->settings,
                'method' => 'saveSettings',
            ]
        ];

        if (get_option('olmec_option_metabox') === 'yes') {
            $hooks['wp_dashboard_setup'] = [
                'instance' => $this->metabox,
                'method' => 'register'
            ];
        }

        return $hooks;
    }

    private function getFilterHooks()
    {
        return [
            'woocommerce_settings_tabs_array' => [
                'instance' => $this->settings,
                'method' => 'addSettingsTab',
                'priority' => 50,
            ],
            'olmec_lowstock_options_settings' => [
                'instance' => $this->settings,
                'method' => 'addSettings',
            ]
        ];
    }
}
