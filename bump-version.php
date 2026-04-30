<?php
/**
 * Automatically bumps the version number in super-elem-pro.php
 * Usage: php bump-version.php [patch|minor|major]
 */

$file_path = __DIR__ . '/super-elem-pro.php';
$content = file_get_contents($file_path);

// Find the current version
preg_match("/define\('SEP_VERSION',\s*'([0-9\.]+)'\);/", $content, $matches);

if (empty($matches[1])) {
    echo "Could not find SEP_VERSION in $file_path\n";
    exit(1);
}

$current_version = $matches[1];
$parts = explode('.', $current_version);

if (count($parts) !== 3) {
    echo "Invalid version format. Expected x.y.z\n";
    exit(1);
}

$type = $argv[1] ?? 'patch';

switch ($type) {
    case 'major':
        $parts[0]++;
        $parts[1] = 0;
        $parts[2] = 0;
        break;
    case 'minor':
        $parts[1]++;
        $parts[2] = 0;
        break;
    case 'patch':
    default:
        $parts[2]++;
        break;
}

$new_version = implode('.', $parts);

// Replace in Plugin Header
$content = preg_replace("/\*\s+Version:\s+([0-9\.]+)/", "* Version:           $new_version", $content);

// Replace in Constants
$content = preg_replace("/define\('SEP_VERSION',\s*'([0-9\.]+)'\);/", "define('SEP_VERSION', '$new_version');", $content);

file_put_contents($file_path, $content);

echo "Bumped version from $current_version to $new_version successfully!\n";
