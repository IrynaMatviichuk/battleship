<?php

namespace Battleship\Application;

use Battleship\Domain\Board;
use Battleship\Domain\BoardRepository;

class StartGameHandler
{
    public function __construct(private BoardRepository $boards) {}

    public function handle(StartGame $command): void
    {
        $board = new Board($command->boardId);

        $this->boards->add($board);
    }
}
