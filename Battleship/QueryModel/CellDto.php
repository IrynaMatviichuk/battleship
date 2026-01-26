<?php

namespace Battleship\QueryModel;

class CellDto implements \JsonSerializable
{
    public function __construct(
        public readonly string $id,
        public readonly ?bool $guessed,
        public readonly string $coordinate,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'guessed' => $this->guessed,
            'coordinates' => $this->coordinate,
        ];
    }
}
