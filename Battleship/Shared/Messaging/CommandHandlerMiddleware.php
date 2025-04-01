<?php

namespace Battleship\Shared\Messaging;

use Battleship\Shared\Middleware;
use Psr\Container\ContainerInterface;

class CommandHandlerMiddleware implements Middleware
{
    public function __construct(private readonly ContainerInterface $container)
    {
    }

    public function handle(object $command, callable $next): void
    {
        $handler = $this->container->get(get_class($command) . 'Handler');
        $handler->handle($command);
    }
}
