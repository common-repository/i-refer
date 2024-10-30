<?php

// Security check - replace 'your_secret_key' with a strong, unique value
if (!isset($_GET['key']) || $_GET['key'] !== 'yEncdkZAd9Vy5uKnjjsvRSn_qDVud97QLavgEPmZmkF7finnvtxJjTznPsLM') {
    die('Unauthorized access');
}

// Ensure that the script can only run if it's not already been disabled
if (strpos(__DIR__, '_disable') !== false) {
    die('The recovery script is disabled.');
}

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'disable-force':
        // Deactivate the plugin first
        // DISABLED TO REMOVE RELIANCE OF WORDPRESS CORE
        #require_once ABSPATH . 'wp-admin/includes/plugin.php';
        #deactivate_plugins('i-refer/i-refer.php'); // Adjust the plugin path as necessary

        // Rename the plugin directory to disable it
        $currentDir = __DIR__;
        $disabledDir = __DIR__ . '_disable';
        if (rename($currentDir, $disabledDir)) {
            echo 'Plugin directory has been renamed and disabled.';
        } else {
            echo 'Failed to rename the plugin directory.';
        }
        break;
    case 'disable':
        // Load WordPress environment
        require_once('../../../wp-load.php');

        // Assuming the security checks are passed, proceed with the plugin deactivation
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        deactivate_plugins('i-refer/i-refer.php');
        echo "Successfully deactivated the plugin.";
        break; // Use the correct plugin path

    case 'force-delete':
        // Deactivate the plugin first, for safety
        // DISABLED TO REMOVE RELIANCE OF WORDPRESS CORE
        #require_once ABSPATH . 'wp-admin/includes/plugin.php';
        #deactivate_plugins('i-refer/i-refer.php'); // Adjust the plugin path as necessary

        // Attempt to delete the plugin directory
        $dir = __DIR__;

        // Recursive delete function
        $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->isDir()){
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($dir);

        echo "Plugin directory has been deleted.";
        break;

    default:
        die('No valid action specified.');
}