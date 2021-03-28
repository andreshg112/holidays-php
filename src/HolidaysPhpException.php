<?php

namespace Andreshg112\HolidaysPhp;

use Exception;

class HolidaysPhpException extends Exception
{
    public static function notEmptyCountry()
    {
        return new self('The country must not be empty.');
    }

    public static function notEmptyLanguage()
    {
        return new self('The language must not be empty.');
    }

    public static function notEmptyTitle()
    {
        return new self('The title must not be empty.');
    }

    public static function notFound()
    {
        return new self('The requested web page could not be found.');
    }

    public static function unrecognizedStructure()
    {
        return new self('The structure of the web page could not be recognized. Maybe it was updated.');
    }
}
