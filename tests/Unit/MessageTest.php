<?php

declare(strict_types=1);

namespace MortarDigital\Example\Tests\Unit;

use MortarDigital\Example\Message;
use PHPUnit\Framework\TestCase;

final class MessageTest extends TestCase
{
    public function testTextUsesThePluginWording(): void
    {
        self::assertSame('Hello from Mortar Example.', Message::text());
    }
}
