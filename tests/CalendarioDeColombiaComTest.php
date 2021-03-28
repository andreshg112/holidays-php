<?php

namespace Andreshg112\HolidaysPhp\Tests;

use Andreshg112\HolidaysPhp\Holiday;
use Andreshg112\HolidaysPhp\HolidaysPhpException;
use Andreshg112\HolidaysPhp\Providers\Colombia\CalendarioDeColombiaCom;
use PHPUnit\Framework\TestCase;

class CalendarioDeColombiaComTest extends TestCase
{
    /** @test */
    public function it_gets_the_holidays()
    {
        $language = 'es';

        $provider = new CalendarioDeColombiaCom($language);

        $years = [2018, 2019, 2020, 2021];

        $year = $years[array_rand($years)];

        $holidays = $provider->holidays($year);

        $this->assertIsArray($holidays);

        $this->assertCount(18, $holidays);

        /** @var \Andreshg112\HolidaysPhp\Holiday */
        foreach ($holidays as $holiday) {
            $this->assertInstanceOf(Holiday::class, $holiday);

            $this->assertSame($year, $holiday->date->year);

            $this->assertSame($language, $holiday->language);
        }
    }

    /** @test */
    public function it_validates_the_language()
    {
        $this->expectException(HolidaysPhpException::class);

        new CalendarioDeColombiaCom('en');
    }

    /** @test */
    public function it_validates_the_year()
    {
        $this->expectException(HolidaysPhpException::class);

        $language = 'es';

        $provider = new CalendarioDeColombiaCom($language);

        $year = 3000; // This year returns 404 in the web page of the provider

        $provider->holidays($year);
    }
}