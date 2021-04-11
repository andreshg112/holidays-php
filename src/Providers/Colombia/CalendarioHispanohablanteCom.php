<?php

namespace Andreshg112\HolidaysPhp\Providers\Colombia;

use Andreshg112\HolidaysPhp\Holiday;
use Andreshg112\HolidaysPhp\HolidaysPhpException;
use Andreshg112\HolidaysPhp\Providers\BaseProvider;
use Jenssegers\Date\Date;
use Wa72\HtmlPageDom\HtmlPage;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class CalendarioHispanohablanteCom extends BaseProvider
{
    protected function baseUrl(): string
    {
        return 'https://calendariohispanohablante.com';
    }

    public function countryTranslations(): array
    {
        return [
            'es' => 'Colombia',
        ];
    }

    public function holidays(int $year = null): ?array
    {
        $year = $year ?? date('Y');

        $baseUrl = $this->baseUrl();

        $url = "{$baseUrl}/{$year}/calendario-colombia-{$year}.html";

        try {
            $html = file_get_contents($url);
        } catch (\Throwable $th) {
            throw HolidaysPhpException::notFound();
        }

        $page = new HtmlPage($html);

        $rows = $page->filter('.group-three > p');

        if ($rows->count() === 0) {
            throw HolidaysPhpException::unrecognizedStructure();
        }

        $holidays = [];

        $country = $this->country();

        foreach ($rows as $row) {
            $crawler = new HtmlPageCrawler($row);

            $dateContainer = $crawler->filter('b')->first();

            // This element must exist
            if ($dateContainer->count() === 0) {
                continue;
            }

            // The text is like "Viernes 1 de Enero", so it has to be separated
            [, $spanishDate] = explode(' ', $dateContainer->text(), 2);

            // The ending : has to be removed
            $spanishDate = rtrim($spanishDate, ':');

            Date::setLocale($this->getLanguage());

            // The date looks this way "1 de Enero 2021"
            $date = Date::createFromFormat('j \d\e F Y', "{$spanishDate} {$year}");

            // Remove it so the text inside the $crawler is only the title of the holiday
            $dateContainer->remove();

            $titleContainer = $crawler->first();

            $holidays[] = new Holiday(
                $country,
                $date->setTime(0, 0),
                $titleContainer->text(),
                $this->getLanguage()
            );
        }

        return $holidays;
    }
}
