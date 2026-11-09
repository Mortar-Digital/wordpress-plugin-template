<?php

declare(strict_types=1);

namespace MortarDigital\Example\Tests\Integration;

use MortarDigital\Example\WordPress\Plugin;
use WP_REST_Request;

final class PluginTest extends IntegrationTestCase
{
    public function testPluginIsBooted(): void
    {
        $this->assertInstanceOf(Plugin::class, Plugin::instance());
        $this->assertSame('Hello from Mortar Example.', mortar_example_message());
    }

    public function testSettingsOptionKeepsOnlyTheNote(): void
    {
        update_option('mortar_example_settings', ['note' => '  Hello  ', 'ignored' => true]);

        $this->assertSame(['note' => 'Hello'], get_option('mortar_example_settings'));
    }

    public function testMessageRouteChecksTheCapability(): void
    {
        require_once ABSPATH . 'wp-admin/includes/user.php';

        $subscriber = wp_insert_user([
            'user_login' => 'mortar_example_subscriber',
            'user_pass' => wp_generate_password(),
            'user_email' => 'mortar_example_subscriber@example.com',
            'role' => 'subscriber',
        ]);
        $admin = wp_insert_user([
            'user_login' => 'mortar_example_admin',
            'user_pass' => wp_generate_password(),
            'user_email' => 'mortar_example_admin@example.com',
            'role' => 'administrator',
        ]);

        $this->assertIsInt($subscriber);
        $this->assertIsInt($admin);

        try {
            wp_set_current_user($subscriber);
            $denied = rest_get_server()->dispatch(new WP_REST_Request('GET', '/mortar-example/v1/message'));
            $this->assertSame(403, $denied->get_status());

            wp_set_current_user($admin);
            $allowed = rest_get_server()->dispatch(new WP_REST_Request('GET', '/mortar-example/v1/message'));
            $this->assertSame(200, $allowed->get_status());
            $data = $allowed->get_data();
            $this->assertSame('Hello from Mortar Example.', is_array($data) ? ($data['message'] ?? null) : null);
        } finally {
            wp_delete_user($subscriber);
            wp_delete_user($admin);
        }
    }

    public function testShortcodeAndBlockPrintTheMessage(): void
    {
        $message = mortar_example_message();

        $this->assertSame('<p class="mortar-example">' . esc_html($message) . '</p>', do_shortcode('[mortar_example]'));
        $this->assertStringContainsString($message, render_block([
            'blockName' => 'mortar-example/notice',
            'attrs' => [],
            'innerBlocks' => [],
            'innerHTML' => '',
            'innerContent' => [],
        ]));
    }
}
