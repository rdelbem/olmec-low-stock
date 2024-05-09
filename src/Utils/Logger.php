<?php

namespace Olmec\LowStock\Utils;

class Logger {

    private static $logFile = WP_CONTENT_DIR . '/plugins/olmec-low-stock/logs/olmec_low_stock_logs.log';
    /**
     * Writes a log message
     *
     * @param string $message The logged message.
     * @param string $level ('INFO', 'ERROR', etc.).
     */
    public static function writeLog($message, $level = 'INFO') {
        self::cleanLogIfNeeded();
        $date = new \DateTime();
        $timestamp = $date->format('Y-m-d H:i:s');
        $logEntry = "{$timestamp} - {$level}: {$message}\n";

        file_put_contents(self::$logFile, $logEntry, FILE_APPEND); 
    }

    private static function cleanLogIfNeeded() {
        if (file_exists(self::$logFile) && filesize(self::$logFile) > 300 * 1024) {
            file_put_contents(self::$logFile, '');
        }
    }

    /**
     * Logs the success message
     *
     * @param string $message The logged message.
     */
    public static function logSuccess($message) {
        self::writeLog($message, 'SUCCESS');
    }

    /**
     * Logs an error message.
     *
     * @param string $message A mensagem a ser logada.
     */
    public static function logError($message) {
        self::writeLog($message, 'ERROR');
    }
}