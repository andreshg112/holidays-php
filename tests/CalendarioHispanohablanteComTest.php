<?php

namespace Andreshg112\HolidaysPhp\Tests;

use PHPUnit\Framework\TestCase;
use Andreshg112\HolidaysPhp\Holiday;
use Andreshg112\HolidaysPhp\HolidaysPhpException;
use Andreshg112\HolidaysPhp\Providers\Colombia\CalendarioHispanohablanteCom;

class CalendarioHispanohablanteComTest extends TestCase
{
    /** @test */
    public function it_gets_the_holidays()
    {
        $language = 'es';

        $provider = new CalendarioHispanohablanteCom($language);

        $years = [2018, 2019, 2020, 2021];

        $year = $years[array_rand($years)];

        $year = 2021;

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

        new CalendarioHispanohablanteCom('en');
    }

    /** @test */
    public function it_validates_the_year()
    {
        $this->expectException(HolidaysPhpException::class);

        $language = 'es';

        $provider = new CalendarioHispanohablanteCom($language);

        $year = 2000; // This year returns 404 in the web page of the provider

        $provider->holidays($year);
    }
}
