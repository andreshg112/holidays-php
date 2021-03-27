<?php

namespace Andreshg112\HolidaysPhp\Tests;

use PHPUnit\Framework\TestCase;
use Andreshg112\HolidaysPhp\Holiday;
use Andreshg112\HolidaysPhp\HolidaysPhpException;
use Andreshg112\HolidaysPhp\Providers\Colombia\PublicHolidaysCo;

class PublicHolidaysCoTest extends TestCase
{
    /** @test */
    public function it_gets_the_holidays()
    {
        $languages = ['en', 'es'];

        $language = $languages[array_rand($languages)];

        $provider = new PublicHolidaysCo($language);

        $years = [2018, 2019, 2020, 2021];

        $year = $years[array_rand($years)];

        $holidays = $provider->holidays($year);

        var_dump($holidays);

        $this->assertIsArray($holidays);

        $this->assertCount(18, $holidays);

        /** @var \Andreshg112\HolidaysPhp\Holiday */
        foreach ($holidays as $holiday) {
            $this->assertInstanceOf(Holiday::class, $holiday);

            $this->assertSame($year, $holiday->date->year);
        }
    }

    /** @test */
    public function it_validates_the_language()
    {
        $this->expectException(HolidaysPhpException::class);

        new PublicHolidaysCo('pt');
    }

    /** @test */
    public function it_validates_the_year()
    {
        $this->expectException(HolidaysPhpException::class);

        $languages = ['en', 'es'];

        $language = $languages[array_rand($languages)];

        $provider = new PublicHolidaysCo($language);

        $year = 2017; // This year returns 404 in the web page of the provider

        $provider->holidays($year);
    }
}
