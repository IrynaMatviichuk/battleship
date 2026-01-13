<?php

namespace Battleship\Domain;

use Exception;

class GuessWasMadeException extends Exception
{
    public function __construct($message = 'Guess was already made for this cell.')
    {
        parent::__construct($message);
    }
}
