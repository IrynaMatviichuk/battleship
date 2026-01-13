<?php

namespace Battleship\Domain;

use Exception;

class InvalidGamePhaseException extends Exception
{
    public function __construct($message = 'Invalid game phase for this action.')
    {
        parent::__construct($message);
    }
}
