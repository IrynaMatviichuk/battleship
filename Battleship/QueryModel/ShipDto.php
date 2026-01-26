<?php

namespace Battleship\QueryModel;

class ShipDto implements \JsonSerializable
{
    public function __construct(
        public readonly string $id,
        public readonly int $size,
        public readonly bool $sunk,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'size' => $this->size,
            'sunk' => $this->sunk,
        ];
    }
}
