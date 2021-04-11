<?php

namespace Andreshg112\HolidaysPhp\Tests\Providers\Colombia;

use Andreshg112\HolidaysPhp\Tests\Providers\ProvidersTestCase;
use Jenssegers\Date\Date;

abstract class ColombiaTestCase extends ProvidersTestCase
{
    protected $country = 'Colombia';

    protected $numberOfHolidays = 18;

    protected function setUp(): void
    {
        parent::setUp();

        // setTime(0, 0) is needed to past the tests
        $this->someHolidayDates = [
            Date::createFromDate(null, 1, 1)->setTime(0, 0),
            Date::createFromDate(null, 5, 1)->setTime(0, 0),
            Date::createFromDate(null, 7, 20)->setTime(0, 0),
            Date::createFromDate(null, 8, 7)->setTime(0, 0),
            Date::createFromDate(null, 12, 8)->setTime(0, 0),
            Date::createFromDate(null, 12, 25)->setTime(0, 0),
        ];
    }
}
