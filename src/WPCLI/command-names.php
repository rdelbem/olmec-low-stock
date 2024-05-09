<?php

namespace Olmec\LowStock\WPCLI;

if(!defined('ABSPATH') && !defined('WP_CLI')){
    exit;
}

use Olmec\LowStock\WPCLI\CronCheck;

$notePressCommands = [
    'olmec-cron' => CronCheck::class,
];