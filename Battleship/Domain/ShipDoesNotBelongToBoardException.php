<?php

namespace Battleship\Domain;

use Exception;

class ShipDoesNotBelongToBoardException extends Exception
{
    public function __construct($message = 'Ship does not belong to board.')
    {
        parent::__construct($message);
    }
}
