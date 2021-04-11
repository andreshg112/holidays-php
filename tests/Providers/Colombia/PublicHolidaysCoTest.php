<?php

namespace Andreshg112\HolidaysPhp\Tests\Providers\Colombia;

use Andreshg112\HolidaysPhp\Providers\Colombia\PublicHolidaysCo;

/** @group forbidden */
class PublicHolidaysCoTest extends ColombiaTestCase
{
    protected $languages = ['en', 'es'];

    protected $provider = PublicHolidaysCo::class;

    protected $unsupportedLanguage = 'pt';

    protected $unsupportedYear = 2017;

    protected $years = [2018, 2019, 2020, 2021];
}
