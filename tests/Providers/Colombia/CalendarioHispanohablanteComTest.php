<?php

namespace Andreshg112\HolidaysPhp\Tests\Providers\Colombia;

use Andreshg112\HolidaysPhp\Providers\Colombia\CalendarioHispanohablanteCom;

class CalendarioHispanohablanteComTest extends ColombiaTestCase
{
    protected $languages = ['es'];

    protected $provider = CalendarioHispanohablanteCom::class;

    protected $unsupportedLanguage = 'en';

    protected $unsupportedYear = 2000;

    protected $years = [2018, 2019, 2020, 2021];
}
