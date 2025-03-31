<?php

namespace Battleship\Shared;

use Closure;

class MiddlewareCommandBus implements CommandBus
{
    private Closure $middlewareChain;

    public function __construct(Middleware ...$middleware)
    {
        $this->middlewareChain = $this->createChain($middleware);
    }

    public function handle(object $command): void
    {
        ($this->middlewareChain)($command);
    }

    private function createChain(array $middleware): Closure
    {
        $then = static fn() => null;

        while ($next = array_pop($middleware)) {
            $then = static fn(object $command) => $next->handle($command, $then);
        }

        return $then;
    }
}
