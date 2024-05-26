<?php

function copyFiles($source, $dest, $exclude = []) {
    $dir = opendir($source);
    @mkdir($dest);

    while (($file = readdir($dir)) !== false) {
        if (!in_array($file, ['.', '..'])) {
            $srcFile = $source . '/' . $file;
            $destFile = $dest . '/' . $file;

            if (is_dir($srcFile)) {
                if (!in_array($file, $exclude)) {
                    copyFiles($srcFile, $destFile, $exclude);
                }
            } else {
                if (!in_array($file, $exclude)) {
                    copy($srcFile, $destFile);
                }
            }
        }
    }

    closedir($dir);
}

$source = __DIR__;
$dest = __DIR__ . '/dist';
$exclude = ['dist', 'tests', '.git', '.github', '.gitignore', 'phpcs.xml', 'generate-stable.php', 'composer.json', 'composer.lock', 'phpunit.xml', 'psalm.xml', 'README.md'];

if (is_dir($dest)) {
    exec('rm -rf ' . escapeshellarg($dest));
}

copyFiles($source, $dest, $exclude);
echo "Build completed successfully!\n";