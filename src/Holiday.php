<?php

namespace Andreshg112\HolidaysPhp;

use Jenssegers\Date\Date;

class Holiday
{
    /** @var string */
    public $country;

    /** @var \Jenssegers\Date\Date */
    public $date;

    /** @var string */
    public $title;

    /** @var string */
    public $language;

    public function __construct(string $country, Date $date, string $title, string $language)
    {
        $country = trim($country);

        if (empty($country)) {
            throw HolidaysPhpException::notEmptyCountry();
        }

        $title = trim($title);

        if (empty($title)) {
            throw HolidaysPhpException::notEmptyTitle();
        }

        $language = trim($language);

        if (empty($language)) {
            throw HolidaysPhpException::notEmptyLanguage();
        }

        $this->country = $country;

        $this->date = $date;

        $this->title = $title;

        $this->language = $language;
    }
}
