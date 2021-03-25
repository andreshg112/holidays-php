<?php

namespace Andreshg112\HolidaysPhp\Tests;

use PHPUnit\Framework\TestCase;
use Andreshg112\HolidaysPhp\HolidaysPhp;

class ExampleTest extends TestCase
{
    /** @test */
    public function it_returns_a_list()
    {
        $object = new HolidaysPhp;

        $holidays = $object->all();

        $this->assertIsArray($holidays);

        $this->assertCount(18, $holidays);
    }
}
