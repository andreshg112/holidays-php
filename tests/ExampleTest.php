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

        $years = [2018, 2019, 2020, 2021];

        $year = $years[array_rand($years)];

        $holidays = $object->get($year);

        // var_dump($holidays);

        $this->assertIsArray($holidays);

        $this->assertCount(18, $holidays);

        foreach ($holidays as $holiday) {
            $this->assertArrayHasKey('date', $holiday);
            $this->assertArrayHasKey('day', $holiday);
            $this->assertArrayHasKey('title', $holiday);
        }
    }
}
