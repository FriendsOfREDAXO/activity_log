<?php

use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class rexActivityTest extends TestCase
{
    /**
     * @throws rex_sql_exception
     */
    public function testMissingMessage(): void
    {
        $this->expectException(rex_exception::class);
        rex_activity::type(rex_activity::TYPE_INFO)->log();
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testActivityLog(): void
    {
        rex_activity::message('My message')->type(rex_activity::TYPE_INFO)->log();
    }
}
