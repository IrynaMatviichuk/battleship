<?php

namespace Battleship\Infrastructure;

use Battleship\Domain\Ship;
use Battleship\Domain\ShipRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineShipRepository implements ShipRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function findById(string $shipId): Ship
    {
        /** @var Ship $ship */
        $ship = $this->entityManager->getRepository(Ship::class)->findOneBy(
            [
                'id' => $shipId
            ]
        );

        if (!$ship) {
            throw new \Exception('Aggregate Not Found');
        }

        return $ship;
    }
}
