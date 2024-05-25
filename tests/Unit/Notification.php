<?php

use Olmec\LowStock\Notification;

define('HOUR_IN_SECONDS', 3600);
define('WP_CONTENT_DIR', '/path/to/somewhere/');

function get_transient($transient)
{
    global $mockGetTransient;
    return $mockGetTransient ? $mockGetTransient->get_transient($transient) : false;
}

function set_transient($transient, $value, $expiration)
{
    global $mockSetTransient;
    return $mockSetTransient ? $mockSetTransient->set_transient($transient, $value, $expiration) : true;
}

function delete_transient($transient)
{
    global $mockDeleteTransient;
    return $mockDeleteTransient ? $mockDeleteTransient->delete_transient($transient) : true;
}

function get_option($option)
{
    global $mockGetOption;
    return $mockGetOption ? $mockGetOption->get_option($option) : false;
}

function wp_mail($to, $subject, $message)
{
    global $mockWpMail;
    return $mockWpMail ? $mockWpMail->wp_mail($to, $subject, $message) : true;
}

function wp_next_scheduled($hook)
{
    global $mockWpNextScheduled;
    return $mockWpNextScheduled ? $mockWpNextScheduled->wp_next_scheduled($hook) : false;
}

function wp_schedule_event($timestamp, $recurrence, $hook)
{
    global $mockWpScheduleEvent;
    return $mockWpScheduleEvent ? $mockWpScheduleEvent->wp_schedule_event($timestamp, $recurrence, $hook) : true;
}

beforeEach(function () {
    global $mockGetTransient, $mockSetTransient, $mockDeleteTransient, $mockGetOption, $mockWpMail, $mockWpNextScheduled, $mockWpScheduleEvent;
    $mockGetTransient = Mockery::mock();
    $mockSetTransient = Mockery::mock();
    $mockDeleteTransient = Mockery::mock();
    $mockGetOption = Mockery::mock();
    $mockWpMail = Mockery::mock();
    $mockWpNextScheduled = Mockery::mock();
    $mockWpScheduleEvent = Mockery::mock();
});

afterEach(function () {
    Mockery::close();
});

test('enqueueNotification should add notification to the queue', function () {
    global $mockGetTransient, $mockSetTransient;

    $mockGetTransient
        ->shouldReceive('get_transient')
        ->once()
        ->with('olmec_low_stock_notifications')
        ->andReturn([]);

    $mockSetTransient
        ->shouldReceive('set_transient')
        ->once()
        ->with('olmec_low_stock_notifications', ['1' => 'Category 1'], HOUR_IN_SECONDS);

    $notification = new Notification();
    $notification->enqueueNotification(1, 'Category 1');
});

test('processNotificationQueue should send notifications and clear the queue', function () {
    global $mockGetTransient, $mockDeleteTransient;

    $mockGetTransient
        ->shouldReceive('get_transient')
        ->once()
        ->with('olmec_low_stock_notifications')
        ->andReturn(['1' => 'Category 1']);

    $mockDeleteTransient
        ->shouldReceive('delete_transient')
        ->once()
        ->with('olmec_low_stock_notifications');

    $notification = Mockery::mock(Notification::class)->makePartial();
    $notification->shouldReceive('sendNotification')
        ->once()
        ->with('Category 1');

    $notification->processNotificationQueue();
});

test('sendNotification should send email if notifications are enabled', function () {
    global $mockGetOption, $mockWpMail;

    $mockGetOption
        ->shouldReceive('get_option')
        ->with('olmec_option_notification')
        ->andReturn('yes');

    $mockGetOption
        ->shouldReceive('get_option')
        ->with('admin_email')
        ->andReturn('admin@example.com');

    $mockWpMail
        ->shouldReceive('wp_mail')
        ->once()
        ->with('admin@example.com', 'Low Stock Notification: Category 1', 'Warning: the stocks for the category "Category 1" are low.')
        ->andReturn(true);

    $loggerMock = Mockery::mock('alias:Olmec\LowStock\Utils\Logger');
    $loggerMock->shouldReceive('logSuccess')
        ->once()
        ->with('Email sent to admin@example.com for low stock of Category 1.');

    $notification = new Notification();
    $notification->sendNotification('Category 1');

    $loggerMock->shouldHaveReceived('logSuccess')
        ->once()
        ->with('Email sent to admin@example.com for low stock of Category 1.');
});

test('sendNotification should log error if email fails', function () {
    global $mockGetOption, $mockWpMail;

    $mockGetOption
        ->shouldReceive('get_option')
        ->with('olmec_option_notification')
        ->andReturn('yes');

    $mockGetOption
        ->shouldReceive('get_option')
        ->with('admin_email')
        ->andReturn('admin@example.com');

    $mockWpMail
        ->shouldReceive('wp_mail')
        ->once()
        ->with('admin@example.com', 'Low Stock Notification: Category 1', 'Warning: the stocks for the category "Category 1" are low.')
        ->andReturn(false);

    $loggerMock = Mockery::mock('alias:Olmec\LowStock\Utils\Logger');
    $loggerMock->shouldReceive('logError')
        ->once()
        ->with('Failed to send email to admin@example.com for low stock of Category 1.');

    $notification = new Notification();
    $notification->sendNotification('Category 1');

    $loggerMock->shouldHaveReceived('logError')
        ->once()
        ->with('Failed to send email to admin@example.com for low stock of Category 1.');
});

test('scheduleEvent should schedule event if not already scheduled', function () {
    global $mockWpNextScheduled, $mockWpScheduleEvent;

    $mockWpNextScheduled
        ->shouldReceive('wp_next_scheduled')
        ->once()
        ->with('olmec_process_notification_queue')
        ->andReturn(false);

    $mockWpScheduleEvent
        ->shouldReceive('wp_schedule_event')
        ->once()
        ->with(Mockery::type('int'), 'hourly', 'olmec_process_notification_queue');

    $notification = new Notification();
    $notification->scheduleEvent();
});