<?php

/**
 * Plugin Name:       Mortar Example
 * Plugin URI:        https://mortar.digital/plugins/example/
 * Description:       Mortar Example is a starting point. Replace this description.
 * Version:           0.1.0
 * Requires at least: 6.6
 * Requires PHP:      8.2
 * Author:            Mortar Digital
 * Author URI:        https://mortar.digital/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       mortar-example
 */

declare(strict_types=1);

use MortarDigital\Example\WordPress\Plugin;

if (! defined('ABSPATH')) {
    exit;
}

// The same package can also run as an MU-plugin.
// Never boot twice.
if (class_exists(Plugin::class) && Plugin::instance() !== null) {
    add_action('admin_notices', static function (): void {
        echo '<div class="notice notice-warning"><p>' . esc_html__('Mortar Example is already loaded as a must-use plugin. Deactivate one of them.', 'mortar-example') . '</p></div>';
    });

    return;
}

spl_autoload_register(static function (string $class): void {
    $prefix = 'MortarDigital\\Example\\';

    if (str_starts_with($class, $prefix)) {
        $file = __DIR__ . '/src/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';

        if (is_file($file)) {
            require $file;
        }
    }
});

require_once __DIR__ . '/functions.php';

Plugin::boot(__DIR__, plugins_url('', __FILE__));
