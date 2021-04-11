<?php

namespace Andreshg112\HolidaysPhp\Tests\Providers\Colombia;

use Andreshg112\HolidaysPhp\Providers\Colombia\CalendarioDeColombiaCom;

/** @group forbidden */
class CalendarioDeColombiaComTest extends ColombiaTestCase
{
    protected $languages = ['es'];

    protected $provider = CalendarioDeColombiaCom::class;

    protected $unsupportedLanguage = 'en';

    protected $unsupportedYear = 3000;

    protected $years = [2018, 2019, 2020, 2021];
}
