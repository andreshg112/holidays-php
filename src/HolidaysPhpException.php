<?php

namespace Andreshg112\HolidaysPhp;

use Exception;

class HolidaysPhpException extends Exception
{
    public static function unrecognizedStructure()
    {
        return new self("The structure of the web page could not be recognized. Maybe it was updated.");
    }
}
