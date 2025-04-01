<?php

namespace Battleship\Application;

use Battleship\Domain\Board;
use Battleship\Domain\BoardRepository;

class StartGameHandler
{
    public function __construct(
        private BoardRepository $boards,
    ) {
    }

    public function handle(StartGame $command): void
    {
        foreach ($command->boardIds as $boardId) {
            $board = new Board($boardId);

            $this->boards->add($board);
        }
    }
}
