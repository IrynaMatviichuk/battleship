<?php

namespace Battleship\Application;

use Battleship\Domain\BoardRepository;
use Battleship\Domain\Coordinate;
use Battleship\Domain\GameRepository;
use Battleship\Domain\ShipRepository;

class PlaceShipHandler
{
    public function __construct(
        private GameRepository $games,
        private BoardRepository $boards,
        private ShipRepository $ships,
    ) {}

    public function handle(PlaceShip $command): void
    {
        $game = $this->games->findById($command->gameId);

        $ship = $this->ships->findById($command->shipId);

        $coordinates = [];

        for ($shift = 0; $shift < $ship->size; $shift++) {
            $direction = in_array($command->direction, ['east', 'south']) ? 1 : -1;

            if (in_array($command->direction, ['north', 'south'])) {
                $coordinates[] = new Coordinate($command->row + ($shift * $direction), $command->column);
            } else {
                $coordinates[] = new Coordinate($command->row, $command->column + ($shift * $direction));
            }
        }

        $game->placeShip($command->shipId, $command->boardId, $coordinates);
    }
}
