<?php

namespace Andreshg112\HolidaysPhp\Providers\Colombia;

use Jenssegers\Date\Date;
use Wa72\HtmlPageDom\HtmlPage;
use Andreshg112\HolidaysPhp\Holiday;
use Andreshg112\HolidaysPhp\HolidaysPhpException;
use Andreshg112\HolidaysPhp\Providers\BaseProvider;

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

        /** @var \DOMElement */
        foreach ($rows as $row) {
            $nodeList = $row->childNodes;

            // It must contain this amount, else, it must be something different than a holiday
            if ($nodeList->count() !== 7) {
                continue;
            }

            /** @var \DOMElement */
            $timeElement = $nodeList->item(3);

            // The date is already in a field time
            $date = Date::parse($timeElement->getAttribute('datetime'));

            /** @var \DOMElement */
            $aElement = $nodeList->item(2);

            $holidays[] = new Holiday(
                $country,
                $date->setTime(0, 0),
                trim($aElement->getAttribute('title')), // title
                $this->getLanguage()
            );
        }

        return $holidays;
    }
}
