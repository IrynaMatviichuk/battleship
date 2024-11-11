<?php

namespace Battleship\Shared;

interface CommandBus
{
    public function handle(object $command): void;
}
