<?php

namespace Olmec\LowStock\WPCLI;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * WP-CLI commands for managing the 'olmec_process_notification_queue' event.
 */
class CronCheck extends \WP_CLI_Command
{
    /**
     * Lists the scheduled 'olmec_process_notification_queue' event.
     *
     * ## EXAMPLES
     *
     *     composer run wpcli olmec-cron list
     *
     */
    public function list($args)
    {
        $event = wp_next_scheduled('olmec_process_notification_queue');
        if ($event) {
            $date = date('Y-m-d H:i:s', $event);
            \WP_CLI::success(sprintf('Next scheduled event for "olmec_process_notification_queue" is at %s.', $date));
        } else {
            \WP_CLI::error('No scheduled event found for "olmec_process_notification_queue".');
        }
    }

    /**
     * Schedules the 'olmec_process_notification_queue' event to run hourly.
     *
     * ## EXAMPLES
     *
     *     composer run wpcli olmec-cron schedule
     *
     */
    public function schedule($args)
    {
        if (!wp_next_scheduled('olmec_process_notification_queue')) {
            wp_schedule_event(time(), 'hourly', 'olmec_process_notification_queue');
            \WP_CLI::success('Scheduled the "olmec_process_notification_queue" event to run hourly.');
        } else {
            \WP_CLI::warning('Event "olmec_process_notification_queue" is already scheduled.');
        }
    }

    /**
     * Unschedules all 'olmec_process_notification_queue' events.
     *
     * ## EXAMPLES
     *
     *     composer run wpcli olmec-cron unschedule
     *
     */
    public function unschedule($args)
    {
        $timestamp = wp_next_scheduled('olmec_process_notification_queue');
        while ($timestamp) {
            wp_unschedule_event($timestamp, 'olmec_process_notification_queue');
            $timestamp = wp_next_scheduled('olmec_process_notification_queue');
        }
        \WP_CLI::success('All scheduled events for "olmec_process_notification_queue" have been unscheduled.');
    }

    /**
     * Runs the 'olmec_process_notification_queue' event immediately.
     *
     * ## EXAMPLES
     *
     *     composer run wpcli olmec-cron execute
     *
     */
    public function execute($args)
    {
        if (wp_next_scheduled('olmec_process_notification_queue')) {
            do_action('olmec_process_notification_queue');
            \WP_CLI::success('Successfully triggered "olmec_process_notification_queue".');
        } else {
            \WP_CLI::error('No event "olmec_process_notification_queue" is currently scheduled.');
        }
    }
}