<?php

namespace Battleship\Domain;

use Battleship\Shared\EventRecorder;

class Board
{
    use EventRecorder;

    public const SIZE = 10;

    private \SplFixedArray $cells;

    public function __construct()
    {
        $this->cells = new \SplFixedArray(self::SIZE);

        foreach (range(0, self::SIZE - 1) as $row)
        {
            $this->cells[$row] = new \SplFixedArray(self::SIZE);

            foreach (range(0, self::SIZE - 1) as $column)
            {
                $this->cells[$row][$column] = new Cell();
            }
        }
    }

    public function guess(Coordinate $coordinate): void
    {
        $cell = $this->getCell($coordinate);

        $this->cells[$coordinate->getRow()][$coordinate->getColumn()] = $cell->guess();

        $this->record(new GuessWasMade($this->getCell($coordinate), $coordinate));
    }

    public function getCell(Coordinate $coordinate): Cell
    {
        return $this->cells[$coordinate->getRow()][$coordinate->getColumn()];
    }

    public function placeShip(Ship $ship): void
    {
        foreach ($ship->getCoordinates() as $coordinate) {
            $cell = $this->getCell($coordinate);

            $this->cells[$coordinate->getRow()][$coordinate->getColumn()] = $cell->occupy();
        }
    }
}
