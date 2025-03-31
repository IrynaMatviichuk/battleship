<?php

namespace Battleship\Application;

use Battleship\Domain\BoardRepository;
use Battleship\Domain\Coordinate;
use Battleship\Domain\ShipRepository;

class PlaceShipHandler
{
    public function __construct(
        private BoardRepository $boards,
        private ShipRepository $ships,
    ) {}

    public function handle(PlaceShip $command): void
    {
        $board = $this->boards->findById($command->boardId);

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

        $board->placeShip($ship, $coordinates);
    }
}
