<?php

namespace Battleship\Infrastructure;

use Battleship\Domain\Cell;
use Battleship\Domain\CellRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineCellRepository implements CellRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function findById(string $cellId): Cell
    {
        $cell = $this->entityManager->getRepository(Cell::class)->findOneBy(
            [
                'id' => $cellId,
            ],
        );

        if (!$cell) {
            throw  new \Exception('Aggregate Not Found');
        }

        return $cell;
    }

    public function add(Cell $cell): void
    {
        $this->entityManager->persist($cell);
        $this->entityManager->flush();
    }
}
