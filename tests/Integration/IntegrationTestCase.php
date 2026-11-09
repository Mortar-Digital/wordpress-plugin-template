<?php

declare(strict_types=1);

namespace MortarDigital\Example\Tests\Integration;

use PHPUnit\Framework\TestCase;

/**
 * Restores the plugin's settings option and the current user after each
 * test, so tests can change both freely.
 */
abstract class IntegrationTestCase extends TestCase
{
    private mixed $savedSettings;

    protected function setUp(): void
    {
        $this->savedSettings = get_option('mortar_example_settings', null);

        wp_set_current_user(0);
    }

    protected function tearDown(): void
    {
        if ($this->savedSettings === null || $this->savedSettings === false) {
            delete_option('mortar_example_settings');
        } else {
            update_option('mortar_example_settings', $this->savedSettings);
        }

        wp_set_current_user(0);
    }
}
