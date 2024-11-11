<?php

namespace Battleship\Domain;

class Ship
{
    public readonly int $id;
    public readonly int $boardId;
    public readonly int $size;

    public function __construct(int $id, int $boardId, int $size)
    {
        $this->id = $id;
        $this->boardId = $boardId;
        $this->size = $size;
    }
}
