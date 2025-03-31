<?php

namespace Battleship\Shared;

use Doctrine\ORM\EntityManager;

class FlushEntityManagerMiddleware implements Middleware
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle(object $message, callable $next): void
    {
        $next($message);
        $this->entityManager->flush();
    }
}
