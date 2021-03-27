<?php

namespace Andreshg112\HolidaysPhp\Providers\Colombia;

use DOMXPath;
use DOMDocument;
use Jenssegers\Date\Date;
use Andreshg112\HolidaysPhp\Holiday;
use Andreshg112\HolidaysPhp\HolidaysPhpException;
use Andreshg112\HolidaysPhp\Providers\BaseProvider;

class PublicHolidaysCo extends BaseProvider
{
    protected function baseUrl(): string
    {
        return 'https://publicholidays.co';
    }

    public function countryTranslations(): array
    {
        return [
            'en' => 'Colombia',
            'es' => 'Colombia',
        ];
    }

    public function holidays(int $year = null): ?array
    {
        $year = $year ?? date('Y');

        $dom = new DOMDocument();

        $baseUrl = $this->baseUrl();

        $url = $this->getLanguage() === 'en' ? "{$baseUrl}/{$year}-dates" : "{$baseUrl}/es/{$year}-dates";

        try {
            ini_set('user_agent', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

            $html = file_get_contents($url);
        } catch (\Throwable $th) {
            var_dump($th->getMessage());

            throw HolidaysPhpException::notFound();
        }

        var_dump($html);

        @$dom->loadHTML($html);

        $finder = new DOMXPath($dom);

        $classname = "publicholidays";

        $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

        if ($nodes->count() === 0) {
            throw HolidaysPhpException::unrecognizedStructure();
        }

        /** @var \DOMElement */
        $table = $nodes->item(0);

        $tbodyList = $table->getElementsByTagName('tbody');

        if ($tbodyList->count() === 0) {
            throw HolidaysPhpException::unrecognizedStructure();
        }

        $tbody = $tbodyList->item(0);

        $trs = $tbody->childNodes;

        if ($trs->count() === 0) {
            throw HolidaysPhpException::unrecognizedStructure();
        }

        $holidays = [];

        $country = $this->country();

        for ($i = 0; $i < $trs->count() - 1; $i++) {
            $tr = $trs->item($i);

            $tds = $tr->childNodes;

            if ($tds->count() !== 3) {
                continue;
            }

            $spanishDate = trim($tds->item(0)->textContent);

            Date::setLocale($this->getLanguage());

            $date = Date::createFromFormat('j F Y', "{$spanishDate} {$year}");

            $holiday = new Holiday(
                $country,
                $date->setTime(0, 0),
                trim($tds->item(1)->textContent), // day
                trim($tds->item(2)->textContent) // title
            );

            $holidays[] = $holiday;
        }

        return $holidays;
    }
}
