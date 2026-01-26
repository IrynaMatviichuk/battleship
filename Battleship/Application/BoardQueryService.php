<?php

namespace Battleship\Application;

use Battleship\QueryModel\BoardDto;
use Battleship\QueryModel\CellDto;
use Battleship\QueryModel\ShipDto;
use Illuminate\Database\DatabaseManager;

class BoardQueryService
{
    public function __construct(
        private readonly DatabaseManager $databaseManager,
    ) {}

    public function getBoard(string $id): BoardDto
    {
        $board = $this->databaseManager->table('boards')->where('id', $id)->first();

        $cells = $this->databaseManager->table('cells')->where('board_id', $board->id)->get();

        $ships = $this->databaseManager->table('ships')->where('board_id', $board->id)->get();

        $cellsDTOs = $cells->map(fn ($cell) => new CellDto($cell->id, $cell->guessed, $cell->coordinate))->toArray();

        $shipsDTOs = $ships->map(fn ($ship) => new ShipDto($ship->id, $ship->size, $ship->sunk))->toArray();

        return new BoardDto($board->id, $board->size, $cellsDTOs, $shipsDTOs);
    }
}
