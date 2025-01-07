<?php

namespace Battleship\Domain;

use Battleship\Shared\EventRecorder;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;

#[Entity]
class Board
{
    use EventRecorder;

    public const DEFAULT_SIZE = 10;

    #[Id, Column(type: "string", unique: true)]
    public readonly string $id;

    #[Column(type: "integer")]
    public readonly int $size;

    private \SplFixedArray $cells;

    public function __construct(string $id)
    {
        $this->id = $id;
        $this->size = self::DEFAULT_SIZE;

        $this->cells = new \SplFixedArray(self::DEFAULT_SIZE);

        foreach (range(0, self::DEFAULT_SIZE - 1) as $row)
        {
            $this->cells[$row] = new \SplFixedArray(self::DEFAULT_SIZE);

            foreach (range(0, self::DEFAULT_SIZE - 1) as $column)
            {
                $id = self::DEFAULT_SIZE * $row + $column + 1;
                $this->cells[$row][$column] = new Cell($id, new Coordinate($row, $column));
            }
        }
    }

    public function guess(Coordinate $coordinate): void
    {
        $cell = $this->getCell($coordinate);
        $cell->guess();

        $this->record(
            new GuessWasMade(
                $cell->id,
                $coordinate,
                $cell->isGuessed(),
                $cell->getShipId(),
            ),
        );
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
            $cell->occupy($ship->id);
        }
    }
}
