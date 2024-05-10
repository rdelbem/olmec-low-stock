<?php

namespace Olmec\LowStock;

if (!defined('ABSPATH')) {
    exit;
}

final class Settings
{
    public function addSettingsTab($settingsTab)
    {
        $settingsTab['olmec_lowstock_options'] = __('Low stock by category', OLMEC_LOW_STOCK_TEXT_DOMAIN);
        return $settingsTab;
    }

    public function addSettings(): void
    {
        woocommerce_admin_fields($this->getSettings());
    }

    public function getSettings(): array
    {
        return [
            'section_title' => [
                'name'     => __('Low Stock by Category Options', OLMEC_LOW_STOCK_TEXT_DOMAIN),
                'type'     => 'title',
                'desc'     => __('Low Stock by category will notify you when an entire category reaches 15 unities', OLMEC_LOW_STOCK_TEXT_DOMAIN),
                'id'       => 'olmec_options_section_title'
            ],
            'olmec_option_metabox_setting' => [
                'name'     => __('Show metabox on dashboard', OLMEC_LOW_STOCK_TEXT_DOMAIN),
                'type'     => 'checkbox',
                'desc'     => __('', OLMEC_LOW_STOCK_TEXT_DOMAIN),
                'id'       => 'olmec_option_metabox',
                'default'  => 'yes',
                'desc_tip' => true,
            ],
            'olmec_option_notification_setting' => [
                'name'     => __('Send email notification to store manager', OLMEC_LOW_STOCK_TEXT_DOMAIN),
                'type'     => 'checkbox',
                'desc'     => __('', OLMEC_LOW_STOCK_TEXT_DOMAIN),
                'id'       => 'olmec_option_notification',
                'default'  => 'yes',
                'desc_tip' => true,
            ],
            'section_end' => [
                'type' => 'sectionend',
                'id'   => 'olmec_options_section_end'
            ]
        ];
    }

    public function saveSettings()
    {
        woocommerce_update_options($this->getSettings());
    }
}
