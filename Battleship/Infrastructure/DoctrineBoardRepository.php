<?php

namespace Battleship\Infrastructure;

use Battleship\Domain\Board;
use Battleship\Domain\BoardRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineBoardRepository implements BoardRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function findById(string $boardId): Board
    {
        /** @var Board $board */
        $board = $this->entityManager->getRepository(Board::class)->findOneBy(
            [
                'id' => $boardId
            ]
        );

        if (! $board) {
            throw new \Exception('Aggregate Not Found');
        }

        return $board;
    }

    public function add(Board $board): void
    {
        $this->entityManager->persist($board);
    }
}
