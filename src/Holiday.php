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
        $this->country = $country;

        $this->date = $date;

        $this->title = $title;

        $this->language = $language;
    }
}
