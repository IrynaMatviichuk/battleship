<?php

namespace Battleship\Domain;

class Player
{
    private int $id;
    private string $gameId;
    private bool $ready;

    public function __construct(int $id, string $gameId)
    {
        $this->id = $id;
        $this->gameId = $gameId;
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
