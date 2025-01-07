<?php

namespace Battleship\Infrastructure;

use Battleship\Domain\Board;
use Battleship\Domain\BoardRepository;

class InMemoryBoardRepository implements BoardRepository
{
    private array $boards;

    public function __construct(array $boards = []) {
        foreach ($boards as $board) {
            $this->boards[$board->id] = $board;
        }
    }

    public function findById(string $boardId): Board
    {
        return $this->boards[$boardId];
    }

    public function add(Board $board): void
    {
        $this->boards[$board->id] = $board;
    }
}
