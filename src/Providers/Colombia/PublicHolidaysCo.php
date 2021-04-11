<?php

namespace Andreshg112\HolidaysPhp\Providers\Colombia;

use Andreshg112\HolidaysPhp\Holiday;
use Andreshg112\HolidaysPhp\HolidaysPhpException;
use Andreshg112\HolidaysPhp\Providers\BaseProvider;
use Jenssegers\Date\Date;
use Wa72\HtmlPageDom\HtmlPage;
use Wa72\HtmlPageDom\HtmlPageCrawler;

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

    public function holidays(int $year = null): array
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

        $rows = $page->filter('.publicholidays > tbody > tr');

        if ($rows->count() === 0) {
            throw HolidaysPhpException::unrecognizedStructure();
        }

        $holidays = [];

        $country = $this->country();

        foreach ($rows as $row) {
            $crawler = new HtmlPageCrawler($row);

            if ($crawler->children()->count() !== 3) {
                continue;
            }

            $spanishDate = trim($crawler->children()->getNode(0)->textContent);

            Date::setLocale($this->getLanguage());

            $date = Date::createFromFormat('j F Y', "{$spanishDate} {$year}");

            $holiday = new Holiday(
                $country,
                $date->setTime(0, 0),
                $crawler->children()->getNode(2)->textContent, // title,
                $this->getLanguage()
            );

            $holidays[] = $holiday;
        }

        return $holidays;
    }
}
