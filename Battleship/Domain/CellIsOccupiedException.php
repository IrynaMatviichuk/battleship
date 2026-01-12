<?php

namespace Battleship\Domain;

use Exception;

class CellIsOccupiedException extends Exception
{
    public function __construct($message = 'Cell is already occupied.')
    {
        parent::__construct($message);
    }
}
