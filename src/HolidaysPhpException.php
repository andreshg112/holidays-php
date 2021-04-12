<?php

namespace Andreshg112\HolidaysPhp;

use Exception;

class HolidaysPhpException extends Exception
{
    public static function notEmptyCountry(): self
    {
        return new self('The country must not be empty.');
    }

    public static function notEmptyLanguage(): self
    {
        return new self('The language must not be empty.');
    }

    public static function notEmptyTitle(): self
    {
        return new self('The title must not be empty.');
    }

    public static function notFound(): self
    {
        return new self('The requested web page could not be found.');
    }

    public static function unrecognizedDate(): self
    {
        return new self('The date of a holiday could not be recognized. Maybe the structure was updated.');
    }

    public static function unrecognizedStructure(): self
    {
        return new self('The structure of the web page could not be recognized. Maybe it was updated.');
    }

    public static function unsupportedCountry(): self
    {
        return new self('The specified country is not supported.');
    }
}
