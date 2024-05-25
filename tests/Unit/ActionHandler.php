<?php

use Tests\ActionHandlerMock;

function add_action($hook, $callback, $priority, $acceptedArgs)
{
    global $mock;
    $mock->add_action($hook, $callback, $priority, $acceptedArgs);
}

function add_filter($hook, $callback, $priority, $acceptedArgs)
{
    global $mock;
    $mock->add_filter($hook, $callback, $priority, $acceptedArgs);
}

test('registerHooks should register WordPress actions correctly', function () {
    global $mock;
    $mock = \Mockery::mock();
    $mock->shouldReceive('add_action')
        ->once()
        ->with('init', \Mockery::any(), 10, 1);

    $handler = new ActionHandlerMock();
    $handler->registerHooks([
        'init' => [
            'instance' => new ActionHandlerMock(),
            'method' => 'onInit',
            'priority' => 10,
            'acceptedArgs' => 1
        ]
    ]);
});

test('registerFilter should register WordPress filters correctly', function () {
    global $mock;
    $mock = \Mockery::mock();
    $mock->shouldReceive('add_filter')
        ->once()
        ->with('the_content', \Mockery::any(), 10, 2);

    $handler = new ActionHandlerMock();
    $handler->registerFilter([
        'the_content' => [
            'instance' => new ActionHandlerMock(),
            'method' => 'modifyContent',
            'priority' => 10,
            'acceptedArgs' => 2
        ]
    ]);
});

afterEach(function () {
    \Mockery::close();
});