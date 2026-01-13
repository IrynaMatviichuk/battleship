<?php

namespace Battleship\Domain;

use Exception;

class WrongNumberOfCoordinatesException extends Exception
{
    public function __construct($message = 'Number of coordinates does not match with the ship size.')
    {
        parent::__construct($message);
    }
}
