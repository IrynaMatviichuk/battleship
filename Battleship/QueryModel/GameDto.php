<?php

namespace Battleship\QueryModel;

class GameDto implements \JsonSerializable
{
    public function __construct(
        public readonly string $id,
        public readonly string $phase,
        public readonly array $boards
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'phase' => $this->phase,
            'boards' => $this->boards,
        ];
    }
}
