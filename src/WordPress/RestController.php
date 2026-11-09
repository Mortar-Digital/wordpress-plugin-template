<?php

declare(strict_types=1);

namespace MortarDigital\Example\WordPress;

final class RestController
{
    public const NAMESPACE = 'mortar-example/v1';

    public function register(): void
    {
        add_action('rest_api_init', $this->registerRoutes(...));
    }

    public function registerRoutes(): void
    {
        register_rest_route(self::NAMESPACE, '/message', [
            'methods' => 'GET',
            'callback' => static fn(): array => ['message' => mortar_example_message()],
            'permission_callback' => static fn(): bool => current_user_can(self::capability()),
        ]);
    }

    /**
     * Capability for the settings screen and this plugin's REST routes.
     * `manage_options` unless `mortar_example_capability` returns another.
     */
    public static function capability(): string
    {
        $capability = apply_filters('mortar_example_capability', 'manage_options');

        return is_string($capability) && $capability !== '' ? $capability : 'manage_options';
    }
}
