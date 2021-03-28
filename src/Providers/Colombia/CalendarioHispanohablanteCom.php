<?php

namespace Andreshg112\HolidaysPhp\Providers\Colombia;

use DOMDocument;
use Jenssegers\Date\Date;
use Wa72\HtmlPageDom\HtmlPage;
use Andreshg112\HolidaysPhp\Holiday;
use Andreshg112\HolidaysPhp\HolidaysPhpException;
use Andreshg112\HolidaysPhp\Providers\BaseProvider;

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

        $dom = new DOMDocument();

        $baseUrl = $this->baseUrl();

        $url = "{$baseUrl}/{$year}/calendario-colombia-{$year}.html";

        try {
            $html = file_get_contents($url);
        } catch (\Throwable $th) {
            throw HolidaysPhpException::notFound();
        }

        $page = new HtmlPage($html);

        $pList = $page->filter('.group-three > p');

        if ($pList->count() === 0) {
            throw HolidaysPhpException::unrecognizedStructure();
        }

        $holidays = [];

        $country = $this->country();

        /** @var \DOMElement */
        foreach ($pList as $pItem) {
            $pChildren = $pItem->childNodes;

            // It must contain 2 nodes, else, it must be something different than a date
            if ($pChildren->count() !== 2) {
                continue;
            }

            // The text is like "Viernes 1 de Enero", so it has to be separated
            [$day, $spanishDate] = explode(' ', trim($pChildren->item(0)->textContent), 2);

            // The ending : has to be removed
            $spanishDate = rtrim($spanishDate, ':');

            Date::setLocale($this->getLanguage());

            // The date looks this way "1 de Enero 2021"
            $date = Date::createFromFormat('j \d\e F Y', "{$spanishDate} {$year}");

            $holidays[] = new Holiday(
                $country,
                $date->setTime(0, 0),
                trim($pChildren->item(1)->textContent), // title
                $this->getLanguage()
            );
        }

        return $holidays;
    }
}
