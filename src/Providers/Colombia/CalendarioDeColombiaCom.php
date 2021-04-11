<?php

namespace Andreshg112\HolidaysPhp\Providers\Colombia;

use Andreshg112\HolidaysPhp\Holiday;
use Andreshg112\HolidaysPhp\HolidaysPhpException;
use Andreshg112\HolidaysPhp\Providers\BaseProvider;
use Jenssegers\Date\Date;
use Wa72\HtmlPageDom\HtmlPage;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class CalendarioDeColombiaCom extends BaseProvider
{
    protected function baseUrl(): string
    {
        return 'https://calendariodecolombia.com';
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

        $url = "{$baseUrl}/calendario-{$year}.html";

        try {
            $html = file_get_contents($url);
        } catch (\Throwable $th) {
            throw HolidaysPhpException::notFound();
        }

        $page = new HtmlPage($html);

        $rows = $page->filter(
            '#cuadro_festivos > div > .tabla_festivos1 > .formato_fechas, #cuadro_festivos > div > .tabla_festivos2 > .formato_fechas'
        );

        if ($rows->count() === 0) {
            throw HolidaysPhpException::unrecognizedStructure();
        }

        $holidays = [];

        $country = $this->country();

        foreach ($rows as $row) {
            $crawler = new HtmlPageCrawler($row);

            $dateContainer = $crawler->filter('time')->first();

            // This element must exist
            if ($dateContainer->count() === 0) {
                continue;
            }

            // The date is already in a field time
            $date = Date::parse($dateContainer->getAttribute('datetime'));

            $titleContainer = $crawler->filter('a')->first();

            $holidays[] = new Holiday(
                $country,
                $date->setTime(0, 0),
                $titleContainer->getAttribute('title'), // title
                $this->getLanguage()
            );
        }

        return $holidays;
    }
}
