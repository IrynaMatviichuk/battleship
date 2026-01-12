<?php

namespace Battleship\Domain;

use Exception;

class InvalidCoordinateRangeException extends Exception
{
    public function __construct($message = 'Invalid coordinate range.')
    {
        parent::__construct($message);
    }
}
