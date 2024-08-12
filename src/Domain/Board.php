<?php

namespace Battleship\Domain;

use Battleship\Shared\EventRecorder;

class Board
{
    use EventRecorder;

    public const SIZE = 10;
    public readonly int $id;

    private \SplFixedArray $cells;

    public function __construct(int $id)
    {
        $this->id = $id;

        $this->cells = new \SplFixedArray(self::SIZE);

        foreach (range(0, self::SIZE - 1) as $row)
        {
            $this->cells[$row] = new \SplFixedArray(self::SIZE);

            foreach (range(0, self::SIZE - 1) as $column)
            {
                $id = self::SIZE * $row + $column + 1;
                $this->cells[$row][$column] = new Cell($id, new Coordinate($row, $column));
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
        if ($ship->boardId !== $this->id) {
            throw new \InvalidArgumentException();
        }

        if ($ship->size !== count($coordinates)) {
            throw new \InvalidArgumentException();
        }

        $cells = $this->getCells($coordinates);

        /** @var Cell $cell */
        foreach ($cells as $cell) {
            $this->cells[$cell->getRow()][$cell->getColumn()] = $cell->occupy($ship->id);
        }
    }
}
