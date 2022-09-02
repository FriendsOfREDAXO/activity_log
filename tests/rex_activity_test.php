<?php

use PHPUnit\Framework\TestCase;

final class rexActivityTest extends TestCase
{
    /**
     * @return void
     * @throws rex_sql_exception
     */
    public function testMissingMessage(): void
    {
        $this->expectException(rex_exception::class);
        rex_activity::type(rex_activity::TYPE_INFO)->log();
    }

    /**
     * @doesNotPerformAssertions
     * @return void
     */
    public function testActivityLog(): void
    {
        rex_activity::message('My message')->type(rex_activity::TYPE_INFO)->log();
    }
}
