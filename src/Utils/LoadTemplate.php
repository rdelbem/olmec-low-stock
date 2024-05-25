<?php 

namespace Olmec\LowStock\Utils;

trait LoadTemplate {
    public function render($templateName, $vars = []) {
        $path = plugin_dir_path(__DIR__) . 'templates/' . $templateName . '.temp.php';
        extract($vars);
        include($path);
    }
}