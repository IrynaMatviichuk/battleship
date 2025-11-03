<?php

namespace Battleship\Domain;

use Battleship\Shared\EventRecorder;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Illuminate\Support\Str;

#[Entity]
class Board
{
    use EventRecorder;

    public const DEFAULT_SIZE = 10;

    public const SHIP_SIZES = [5, 4, 3, 3, 2];

    #[Id, Column(type: "string", unique: true)]
    public readonly string $id;

    #[Column(type: "integer")]
    public readonly int $size;

    #[OneToMany(targetEntity: Cell::class, mappedBy: 'board', cascade: ['persist'])]
    private Collection $cells;

    #[OneToMany(targetEntity: Ship::class, mappedBy: 'board', cascade: ['persist'])]
    private Collection $ships;

    private string $gameId;

    public function __construct(string $id, string $gameId)
    {
        $this->id = $id;
        $this->gameId = $gameId;
        $this->size = self::DEFAULT_SIZE;

        $this->cells = new ArrayCollection();
        $this->ships = new ArrayCollection();

        foreach (range(0, self::DEFAULT_SIZE - 1) as $row)
        {
            foreach (range(0, self::DEFAULT_SIZE - 1) as $column)
            {
                $this->cells[] = new Cell(Str::uuid(), $this, new Coordinate($row, $column));
            }
        }

        foreach (self::SHIP_SIZES as $size) {
            $this->ships[] = new Ship(Str::uuid(), $this, $size);
        }
    }

    public function getShips(): Collection
    {
        return $this->ships;
    }

    public function guess(Coordinate $coordinate): void
    {
        $cell = $this->getCell($coordinate);
        $cell->guess();

        $shipId = $cell->getShip();

        $this->record(
            new GuessWasMade(
                $cell->id,
                $coordinate,
                $cell->isGuessed(),
                $shipId,
            ),
        );

        if ($shipId && $this->shipHasSunk($shipId)) {
            $this->record(new ShipHasSunk($shipId));
        }

        if ($this->allShipsHasSunk()) {
            $this->record(new GameOver($this->id));
        }
    }

    private function allShipsHasSunk(): bool
    {
        $sunkShipsCount = $this->ships->filter(function (Ship $ship) {
            return $ship->sunk();
        })->count();

        return $sunkShipsCount === $this->ships->count();
    }

    public function shipHasSunk(string $shipId): bool
    {
        $unguessedCellsCount = $this->cells->filter(function (Cell $cell) use ($shipId) {
           return $cell->getShip() === $shipId && !$cell->isGuessed();
        })->count();

        if ($unguessedCellsCount === 0) {
            $ship = $this->ships->findFirst(function ($key, Ship $ship) use ($shipId) {
                return $ship->id === $shipId;
            });

            if (!$ship) {
                throw new \InvalidArgumentException('Ship not found');
            }

            $ship->markAsSunk();

            return true;
        }

        return false;
    }

    public function getCell(Coordinate $coordinate): Cell
    {
        $cell = $this->cells->findFirst(function ($key, Cell $cell) use ($coordinate) {
          return $cell->hasCoordinate($coordinate);
        });

        if (!$cell) {
            throw new \InvalidArgumentException('Cell not found');
        }

        return $cell;
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
        if ($ship->board->id !== $this->id) {
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
