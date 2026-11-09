<?php

/**
 * Runs when the plugin is deleted from the Plugins screen. Stored data stays
 * unless the site opts in, because a delete should not throw away anything
 * you might still need:
 *
 *     define('MORTAR_EXAMPLE_DELETE_DATA_ON_UNINSTALL', true); // wp-config.php
 *
 * On multisite the constant applies to every site. If you add a cron event,
 * clear it here as well as on deactivation. If you add a table, drop it inside
 * the same callback, the way Mortar Consent drops its log table.
 */

declare(strict_types=1);

if (! defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Options are per site, so a network uninstall has to visit every site.
$mortar_example_uninstall = static function (): void {
    if (! defined('MORTAR_EXAMPLE_DELETE_DATA_ON_UNINSTALL') || MORTAR_EXAMPLE_DELETE_DATA_ON_UNINSTALL !== true) {
        return;
    }

    delete_option('mortar_example_settings');
};

if (is_multisite()) {
    foreach (get_sites(['fields' => 'ids', 'number' => 0]) as $mortar_example_site) {
        switch_to_blog((int) $mortar_example_site);
        $mortar_example_uninstall();
        restore_current_blog();
    }
} else {
    $mortar_example_uninstall();
}
