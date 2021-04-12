<?php

namespace Andreshg112\HolidaysPhp\Tests\Providers;

use Andreshg112\HolidaysPhp\Holiday;
use Andreshg112\HolidaysPhp\HolidaysPhpException;
use PHPUnit\Framework\TestCase;

abstract class ProvidersTestCase extends TestCase
{
    /** @var string The country of the provider. */
    protected $country;

    /** @var array The supported languages of the provider. */
    protected $languages;

    /** @var int The amount of holidays. */
    protected $numberOfHolidays;

    /** @var string The fully qualified provider class name to test. */
    protected $provider;

    /** @var array Some holidays that always are on the same day. */
    protected $someHolidayDates;

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

            $this->assertSame($this->country, $holiday->country);

            $this->assertSame($year, $holiday->date->year);

            $this->assertNotEmpty($holiday->title);

            $this->assertSame($language, $holiday->language);
        }

        /** @var \Jenssegers\Date\Date */
        foreach ($this->someHolidayDates as $someHolidayDate) {
            // Set the tested year so it's the same as the results
            $someHolidayDate->year($year);

            $contained = false;

            /** @var \Andreshg112\HolidaysPhp\Holiday */
            foreach ($holidays as $holiday) {
                // If date is the same, $someHoliday is inside the $holidays
                if ($someHolidayDate->eq($holiday->date)) {
                    $contained = true;

                    break;
                }
            }

            $this->assertTrue($contained, "The holiday {$someHolidayDate->toDateString()} is not contained.");
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
