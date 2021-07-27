<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Yutsuku\WordPress\Slug;

/**
 * @coversDefaultClass Yutsuku\WordPress\Slug
 */
final class SlugTest extends TestCase
{
    public function testValidName(): void
    {
        $this->assertEquals(
            'my-lovely-users-table',
            Slug::NAME
        );
    }
}