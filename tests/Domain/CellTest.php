<?php

namespace Tests\Battleship\Domain;

use Battleship\Domain\Board;
use Battleship\Domain\Cell;
use Battleship\Domain\Coordinate;
use Battleship\Domain\Ship;
use PHPUnit\Framework\TestCase;

class CellTest extends TestCase
{
    public function test_it_creates_cell(): void
    {
        $this->expectNotToPerformAssertions();

        $board = new Board('board_id');

        new Cell('cell_id', $board, new Coordinate(0, 0));
    }

    public function test_it_matches_coordinate(): void
    {
        $board = new Board('board_id');

        $cell = new Cell('cell_id', $board, new Coordinate(3, 4));

        $this->assertTrue($cell->hasCoordinate(new Coordinate(3, 4)));
    }

    public function test_it_does_not_match_coordinate(): void
    {
        $board = new Board('board_id');

        $cell = new Cell('cell_id', $board, new Coordinate(3, 4));

        $this->assertFalse($cell->hasCoordinate(new Coordinate(0, 0)));
    }

    public function test_it_gets_occupied_by_ship(): void
    {
        $board = new Board('board_id');

        $cell = new Cell('cell_id', $board, new Coordinate(3, 4));

        $ship = new Ship('ship_id', $board, 3);

        $cell->occupy($ship);

        $this->assertEquals($ship, $cell->getShip());
    }

    public function test_it_throws_when_occupied(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $board = new Board('board_id');

        $cell = new Cell('cell_id', $board, new Coordinate(3, 4));

        $ship = new Ship('ship_id', $board, 3);

        $cell->occupy($ship);

        $cell->occupy(new Ship('ship_id_2', $board, 1));
    }

    public function test_it_is_not_guessed(): void
    {
        $board = new Board('board_id');

        $cell = new Cell('cell_id', $board, new Coordinate(3, 4));

        $this->assertNull($cell->isGuessed());
    }

    public function test_it_is_missed(): void
    {
        $board = new Board('board_id');

        $cell = new Cell('cell_id', $board, new Coordinate(3, 4));

        $cell->guess();

        $this->assertFalse($cell->isGuessed());
    }

    public function test_it_is_guessed(): void
    {
        $board = new Board('board_id');

        $cell = new Cell('cell_id', $board, new Coordinate(3, 4));

        $ship = new Ship('ship_id', $board, 3);

        $cell->occupy($ship);

        $cell->guess();

        $this->assertTrue($cell->isGuessed());
    }
}
