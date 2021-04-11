<?php

namespace Andreshg112\HolidaysPhp\Providers;

use Andreshg112\HolidaysPhp\HolidaysPhpException;

abstract class BaseProvider
{
    protected $language;

    public function __construct(string $language)
    {
        if (!in_array($language, $this->supportedLanguages())) {
            throw new HolidaysPhpException(
                "The language code {$language} is not supported by the provider " . static::class
            );
        }

        $this->language = $language;
    }

    /**
     * Returns the base URL of the provider.
     *
     * @return string
     */
    abstract protected function baseUrl(): string;

    /**
     * Returns the name of the country according to the language specified in the constructor.
     *
     * @return string
     */
    public function country(): string
    {
        return $this->countryTranslations()[$this->language];
    }

    /**
     * Returns a list of the translations of the country name.
     * It must have an structure like this: `[ ['en' => 'United States', 'es' => 'Estados Unidos'] ]`.
     * Every language code, the key of each array, will be a supported language.
     *
     * @return string
     */
    abstract public function countryTranslations(): array;

    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * Returns a list of holidays based on the year and language specified in the constructor.
     *
     * @param int $year Must be supported by the provider. By default, the current year.
     * @return array
     * @throws \Andreshg112\HolidaysPhp\HolidaysPhpException
     */
    abstract public function holidays(int $year = null): array;

    /**
     * Returns a list of the languages supported by the provider specified in `countryTranslations()`.
     *
     *
     * @return array
     */
    public function supportedLanguages(): array
    {
        return array_keys($this->countryTranslations());
    }
}
