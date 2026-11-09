<?php

declare(strict_types=1);

namespace MortarDigital\Example;

/**
 * Framework-agnostic code lives in src/, outside src/WordPress/, so the unit
 * tests can run it without booting WordPress.
 */
final class Message
{
    public static function text(): string
    {
        return __('Hello from Mortar Example.', 'mortar-example');
    }
}
