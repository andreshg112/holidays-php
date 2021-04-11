<?php

namespace Andreshg112\HolidaysPhp;

use HaydenPierce\ClassFinder\ClassFinder;

class Fallback
{
    protected $country;

    protected $language;

    public function __construct(string $country, string $language)
    {
        $country = trim($country);

        if (!is_dir(__DIR__ . "/Providers/{$country}")) {
            throw HolidaysPhpException::unsupportedCountry();
        }

        $this->country = $country;

        $this->language = trim($language);
    }

    public function holidays(int $year = null): array
    {
        $namespace = __NAMESPACE__ . "\\Providers\\{$this->country}";

        $classes = ClassFinder::getClassesInNamespace($namespace);

        $holidays = [];

        foreach ($classes as $class) {
            try {
                /** @var \Andreshg112\HolidaysPhp\Providers\BaseProvider */
                $provider = new $class($this->language);

                $holidays = $provider->holidays($year);

                if (!empty($holidays)) {
                    break;
                }
            } catch (\Throwable $th) {
                continue;
            }
        }

        return $holidays;
    }
}
