<?php

namespace Tests\Battleship\Domain;

use Battleship\Domain\Board;
use Battleship\Domain\Cell;
use Battleship\Domain\Coordinate;
use Battleship\Domain\Ship;
use PHPUnit\Framework\TestCase;

class ShipTest extends  TestCase
{
    public function test_it_sunk(): void
    {
        $board = new Board('board_id');

        $cell1 = new Cell('cell_id_1', $board, new Coordinate(3, 4));
        $cell2 = new Cell('cell_id_2', $board, new Coordinate(3, 5));
        $cell3 = new Cell('cell_id_3', $board, new Coordinate(3, 6));


        $ship = new Ship('ship_id', $board, 3);

        $cell1->occupy($ship);
        $cell2->occupy($ship);
        $cell3->occupy($ship);

        $events = $ship->recordedMessages();
        $this->assertCount(0, $events);

        $cell1->guess();
        $cell2->guess();
        $cell3->guess();

        $events = $ship->recordedMessages();
        $this->assertCount(1, $events);

        $event = $events[0];
        $this->assertInstanceOf(ShipHasSunk::class, $event);
    }
}
