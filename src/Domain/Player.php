<?php

namespace Battleship\Domain;

class Player
{
    private int $id;
    private bool $ready;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->ready = false;
    }
}
