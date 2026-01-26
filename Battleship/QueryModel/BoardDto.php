<?php

namespace Battleship\QueryModel;

class BoardDto implements \JsonSerializable
{
    public function __construct(
        public readonly string $id,
        public readonly int $size
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'size' => $this->size,
        ];
    }
}
