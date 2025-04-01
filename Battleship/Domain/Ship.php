<?php

namespace Battleship\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity]
class Ship
{
    #[Id, Column(type: "string", unique: true)]
    public readonly string $id;

    #[ManyToOne(targetEntity: Board::class, inversedBy: 'ships')]
    public readonly Board $board;

    #[Column(type: "integer")]
    public readonly int $size;

    #[OneToMany(targetEntity: Cell::class, mappedBy: 'ship', cascade: ['persist'])]
    private Collection $cells;

    public function __construct(string $id, Board $board, int $size)
    {
        $this->id = $id;
        $this->board = $board;
        $this->size = $size;
    }
}
