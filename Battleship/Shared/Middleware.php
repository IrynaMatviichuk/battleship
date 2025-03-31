<?php

namespace Battleship\Shared;

interface Middleware
{
    public function handle(object $message, callable $next): void;
}
