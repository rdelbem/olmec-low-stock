<?php

namespace Olmec\LowStock;

if (!defined('ABSPATH')) {
    exit;
}

use Olmec\LowStock\Utils\Logger;

final class Notification
{
    public function enqueueNotification($categoryId, $categoryName)
    {
        $queue = get_transient('olmec_low_stock_notifications') ?: [];
        $queue[$categoryId] = $categoryName;
        set_transient('olmec_low_stock_notifications', $queue, HOUR_IN_SECONDS);
    }

    public function processNotificationQueue()
    {
        $queue = get_transient('olmec_low_stock_notifications');
        if ($queue) {
            foreach ($queue as $categoryId => $categoryName) {
                $this->sendNotification($categoryName);
            }
            delete_transient('olmec_low_stock_notifications');
        }
    }

    public function sendNotification($categoryName)
    {
        if (get_option('olmec_option_notification') !== 'yes') {
            return;
        }

        $adminEmail = get_option('admin_email');
        $subject = 'Low Stock Notification: ' . $categoryName;
        $message = 'Warning: the stocks for the category "' . $categoryName . '" are low.';

        if (!wp_mail($adminEmail, $subject, $message)) {
            return Logger::logError("Failed to send email to {$adminEmail} for low stock of {$categoryName}.");
        }

        return Logger::logSuccess("Email sent to {$adminEmail} for low stock of {$categoryName}.");
    }

    public function scheduleEvent(): void
    {
        if (!wp_next_scheduled('olmec_process_notification_queue')) {
            wp_schedule_event(time(), 'hourly', 'olmec_process_notification_queue');
        }
    }
}
