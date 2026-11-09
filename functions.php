<?php

declare(strict_types=1);

use MortarDigital\Example\Message;

if (! defined('ABSPATH')) {
    exit;
}

if (! function_exists('mortar_example_message')) {
    /**
     * Public function for themes and other plugins. Replace it with the
     * real API, and keep the function_exists() guard so an MU-plugin copy
     * and a normal copy can both load this file.
     */
    function mortar_example_message(): string
    {
        return Message::text();
    }
}
