<?php

namespace Battleship\Application;

use Battleship\Domain\Board;
use Battleship\Domain\Cell;
use Battleship\Domain\Coordinate;
use Battleship\Domain\BoardRepository;
use Battleship\Domain\CellRepository;

class StartGameHandler
{
    public function __construct(
        private BoardRepository $boards,
        private CellRepository $cells,
    ) {
    }

    public function handle(StartGame $command): void
    {
        $board = new Board($command->boardId);

        $this->boards->add($board);

        $cell = new Cell('uuid', new Coordinate(1, 3));

        $this->cells->add($cell);

        $cell = $this->cells->findById('uuid');

        var_dump($cell);
    }
}
