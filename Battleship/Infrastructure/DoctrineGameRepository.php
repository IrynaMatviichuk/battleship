<?php

namespace Battleship\Infrastructure;

use Battleship\Domain\Board;
use Battleship\Domain\BoardRepository;
use Battleship\Domain\Game;
use Battleship\Domain\GameRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineGameRepository implements GameRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function findById(string $gameId): Game
    {
        /** @var Game $game */
        $game = $this->entityManager->getRepository(Game::class)->findOneBy(
            [
                'id' => $gameId
            ]
        );

        if (! $game) {
            throw new \Exception('Aggregate Not Found');
        }

        return $game;
    }

    public function add(Game $game): void
    {
        $this->entityManager->persist($game);
    }
}
