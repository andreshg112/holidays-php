<?php

namespace Andreshg112\HolidaysPhp\Tests\Providers;

use Andreshg112\HolidaysPhp\Holiday;
use Andreshg112\HolidaysPhp\HolidaysPhpException;
use PHPUnit\Framework\TestCase;

abstract class ProvidersTestCase extends TestCase
{
    /** @var array The supported languages of the provider. */
    protected $languages;

    /** @var int The amount of holidays. */
    protected $numberOfHolidays;

    /** @var string The fully qualified provider class name to test. */
    protected $provider;

    /** @var string A language code not supported by the provider. */
    protected $unsupportedLanguage;

    /** @var string A year not supported by the provider. It will return a 404 error page. */
    protected $unsupportedYear;

    /** @var array Some supported years of the provider. */
    protected $years;

    /** @test */
    public function it_gets_the_holidays()
    {
        $language = $this->languages[array_rand($this->languages)];

        $provider = new $this->provider($language);

        $year = $this->years[array_rand($this->years)];

        $holidays = $provider->holidays($year);

        $this->assertIsArray($holidays);

        $this->assertCount($this->numberOfHolidays, $holidays);

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

        new $this->provider($this->unsupportedLanguage);
    }

    /** @test */
    public function it_validates_the_year()
    {
        $this->expectException(HolidaysPhpException::class);

        $language = $this->languages[array_rand($this->languages)];

        $provider = new $this->provider($language);

        $provider->holidays($this->unsupportedYear);
    }
}
