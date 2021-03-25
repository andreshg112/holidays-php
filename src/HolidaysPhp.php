<?php

namespace Andreshg112\HolidaysPhp;

use DOMXPath;
use DOMDocument;

class HolidaysPhp
{
    public function all(): ?array
    {
        $dom = new DOMDocument();

        @$dom->loadHTMLFile('https://publicholidays.co/es/2020-dates');

        $finder = new DOMXPath($dom);

        $classname = "publicholidays";

        $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

        if (empty($nodes)) {
            return null;
        }

        /** @var \DOMElement */
        $table = $nodes->item(0);

        $tbodyList = $table->getElementsByTagName('tbody');

        if (empty($tbodyList)) {
            return null;
        }

        $tbody = $tbodyList->item(0);

        $trs = $tbody->childNodes;

        if (empty($trs)) {
            return null;
        }

        $holidays = [];

        for ($i = 0; $i < $trs->count() - 1; $i++) {
            $tr = $trs->item($i);

            $tds = $tr->childNodes;

            if ($tds->count() !== 3) {
                continue;
            }

            $holiday = [
                'date' => trim($tds->item(0)->textContent),
                'day' => trim($tds->item(1)->textContent),
                'name' => trim($tds->item(2)->textContent),
            ];

            $holidays[] = $holiday;
        }

        return $holidays;
    }
}
