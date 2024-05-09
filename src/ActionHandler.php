<?php

namespace Olmec\LowStock;

if (!defined('ABSPATH')) {
    exit;
}

abstract class ActionHandler {
    /**
     * Process and register acction hooks based on an array of configurations.
     *
     * @param array $hooks Associative array of hook configurations, where each configuration can now include an 'instance' key.
     */
    protected function registerHooks(array $hooks) {
        foreach ($hooks as $hook => $config) {
            $instance = $config['instance'] ?? $this;
            $method = $config['method'];
            $priority = $config['priority'] ?? 10;
            $acceptedArgs = $config['acceptedArgs'] ?? 1;

            add_action($hook, [$instance, $method], $priority, $acceptedArgs);
        }
    }

    /**
     * Process and register filter hooks based on an array of configurations.
     *
     * @param array $hooks Associative array of hook configurations, where each configuration can now include an 'instance' key.
     */
    protected function registerFilter(array $hooks) {
        foreach ($hooks as $hook => $config) {
            $instance = $config['instance'] ?? $this;
            $method = $config['method'];
            $priority = $config['priority'] ?? 10;
            $acceptedArgs = $config['acceptedArgs'] ?? 1;

            add_filter($hook, [$instance, $method], $priority, $acceptedArgs);
        }
    }
}