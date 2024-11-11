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

    public function markReady(): void
    {
        $this->ready = true;
    }

    public function isReady(): bool
    {
        return $this->ready;
    }
}
