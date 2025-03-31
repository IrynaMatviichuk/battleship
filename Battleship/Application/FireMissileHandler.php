<?php

namespace Battleship\Application;

use Battleship\Domain\BoardRepository;

class FireMissileHandler
{
    public function __construct(private BoardRepository $boards) {}

    public function handle(FireMissile $command): void
    {
        $board = $this->boards->findById($command->boardId);

        $board->guess($command->coordinate);

        $this->boards->add($board);
    }
}
