<?php

namespace Battleship\Application;

use Battleship\Domain\BoardRepository;
use Battleship\Domain\Coordinate;

class MakeGuessHandler
{
    public function __construct(
        private BoardRepository $boards,
    ) {}

    public function handle(MakeGuess $command): void
    {
        $board = $this->boards->findById($command->boardId);

        $board->guess(new Coordinate($command->row, $command->column));
    }
}
