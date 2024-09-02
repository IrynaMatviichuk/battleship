<?php

namespace Battleship\Infrastructure;

use Battleship\Domain\Board;
use Battleship\Domain\BoardRepository;

class InMemoryBoardRepository implements BoardRepository
{
    private array $boards;
    public function __construct(array $boards) {
        foreach ($boards as $board) {
            $this->boards[$board->id] = $board;
        }
    }

    public function findById(int $boardId): Board
    {
        return $this->boards[$boardId];
    }
}
