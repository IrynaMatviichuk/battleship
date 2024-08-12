<?php

namespace Battleship\Infrastructure;

use Battleship\Domain\Board;
use Battleship\Domain\BoardRepository;

class InMemoryBoardRepository implements BoardRepository
{
    public function __construct(private array $boards) {}
    public function findById(int $boardId): Board
    {
        return $this->boards[$boardId];
    }
}
