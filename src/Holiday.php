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
    public $day;

    /** @var string */
    public $title;

    public function __construct(string $country, Date $date, string $day, string $title)
    {
        $this->country = $country;

        $this->date = $date;

        $this->day = $day;

        $this->title = $title;
    }
}
