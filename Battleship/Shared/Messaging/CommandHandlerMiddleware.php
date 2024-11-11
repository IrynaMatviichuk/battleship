<?php

namespace Battleship\Shared\Messaging;

use Battleship\Shared\CommandBus;
use Psr\Container\ContainerInterface;

class CommandHandlerMiddleware implements CommandBus
{
    public function __construct(private readonly ContainerInterface $container)
    {
    }

    public function handle(object $command): void
    {
        $handler = $this->container->get(get_class($command) . 'Handler');
        $handler->handle($command);
    }
}
