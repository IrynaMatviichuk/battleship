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
                $this->cells[$row][$column] = new Cell(new Coordinate($row, $column));
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

    public function getCells(array $coordinates): array
    {
        $cells = [];

        foreach ($coordinates as $coordinate) {
            $cells[] = $this->getCell($coordinate);
        }

        return $cells;
    }

    public function placeShip(Ship $ship, array $coordinates): void
    {
        $cells = $this->getCells($coordinates);

        $ship->place($cells);

        foreach ($ship->getCells() as $occupiedCell) {
            $this->cells[$occupiedCell->getRow()][$occupiedCell->getColumn()] = $occupiedCell;
        }
    }
}
