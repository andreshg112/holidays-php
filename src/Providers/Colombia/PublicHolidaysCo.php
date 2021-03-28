<?php

namespace Andreshg112\HolidaysPhp\Providers\Colombia;

use Jenssegers\Date\Date;
use Wa72\HtmlPageDom\HtmlPage;
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

        $baseUrl = $this->baseUrl();

        $url = $this->getLanguage() === 'en' ? "{$baseUrl}/{$year}-dates" : "{$baseUrl}/es/{$year}-dates";

        try {
            $html = file_get_contents($url);
        } catch (\Throwable $th) {
            throw HolidaysPhpException::notFound();
        }

        $page = new HtmlPage($html);

        $trs = $page->filter('.publicholidays > tbody > tr');

        if ($trs->count() === 0) {
            throw HolidaysPhpException::unrecognizedStructure();
        }

        $holidays = [];

        $country = $this->country();

        /** @var \DOMElement */
        foreach ($trs as $tr) {
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
                trim($tds->item(2)->textContent), // title,
                $this->getLanguage()
            );

            $holidays[] = $holiday;
        }

        return $holidays;
    }
}
