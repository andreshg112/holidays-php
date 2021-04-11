<?php

namespace Andreshg112\HolidaysPhp\Tests;

use Andreshg112\HolidaysPhp\Fallback;
use Andreshg112\HolidaysPhp\Holiday;
use PHPUnit\Framework\TestCase;

class FallbackTest extends TestCase
{
    /** @test */
    public function it_gets_the_holidays_of_colombia()
    {
        $languages = ['en', 'es'];

        $language = $languages[array_rand($languages)];

        $fallback = new Fallback('Colombia', $language);

        $years = [2018, 2019, 2020, 2021];

        $year = $years[array_rand($years)];

        $holidays = $fallback->holidays($year);

        $this->assertIsArray($holidays);

        $this->assertCount(18, $holidays);

        /** @var \Andreshg112\HolidaysPhp\Holiday */
        foreach ($holidays as $holiday) {
            $this->assertInstanceOf(Holiday::class, $holiday);

            $this->assertSame($year, $holiday->date->year);

            $this->assertSame($language, $holiday->language);
        }
    }
}
