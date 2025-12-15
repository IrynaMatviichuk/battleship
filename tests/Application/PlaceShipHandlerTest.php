<?php

namespace Tests\Battleship\Application;

use Battleship\Application\PlaceShip;
use Battleship\Application\PlaceShipHandler;
use Battleship\Domain\Board;
use Battleship\Domain\Coordinate;
use Battleship\Domain\Ship;
use Battleship\Domain\Game;
use Battleship\Infrastructure\InMemoryBoardRepository;
use Battleship\Infrastructure\InMemoryShipRepository;
use Battleship\Infrastructure\InMemoryGameRepository;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class PlaceShipHandlerTest extends TestCase
{
    public function test_it_places_ship(): void
    {
        $game = Game::startGame('game_id', 'board_id', 'board_2');

        $games = new InMemoryGameRepository([$game]);

        $board = $game->getBoards()['board_id'];
        $boards = new InMemoryBoardRepository([$board]);

        $ship = $board->getShips()->findFirst(function ($key, Ship $ship) {
            return $ship->size === 2;
        });
        $ships = new InMemoryShipRepository([$ship->id => $ship]);

        $command = new PlaceShip($game->id, $board->id, $ship->id, 2, 3, 'east');

        $placeShipHandler = new PlaceShipHandler($games, $boards, $ships);

        $placeShipHandler->handle($command);

        $cell1 = $board->getCell(new Coordinate(2, 3));
        $cell2 = $board->getCell(new Coordinate(2, 4));

        $this->assertEquals($ship->id, $cell1->getShip());
        $this->assertEquals($ship->id, $cell2->getShip());
    }

    public function test_it_places_ship_from_other_board(): void
    {
        $this->expectExceptionMessage('Ship does not belong to board');

        $game = Game::startGame('game_id', 'board_id', 'other_board_id');

        $games = new InMemoryGameRepository([$game]);

        $board = $game->getBoards()['board_id'];
        $otherBoard = $game->getBoards()['other_board_id'];

        $boards = new InMemoryBoardRepository([$board, $otherBoard]);

        $ship = $board->getShips()->findFirst(function ($key, Ship $ship) {
            return $ship->size === 2;
        });

        $ships = new InMemoryShipRepository([$ship->id => $ship]);

        $command = new PlaceShip($game->id, $otherBoard->id, $ship->id, 2, 3, 'east');

        $placeShipHandler = new PlaceShipHandler($games, $boards, $ships);

        $placeShipHandler->handle($command);
    }

    public function test_it_places_ship_second_time(): void
    {
        $this->expectExceptionMessage('Cell is already occupied');

        $game = Game::startGame('game_id', 'board_id', 'other_board_id');

        $games = new InMemoryGameRepository([$game]);

        $board = $game->getBoards()['board_id'];
        $otherBoard = $game->getBoards()['other_board_id'];

        $boards = new InMemoryBoardRepository([$board, $otherBoard]);

        $ship = $board->getShips()->findFirst(function ($key, Ship $ship) {
            return $ship->size === 2;
        });

        $ships = new InMemoryShipRepository([$ship->id => $ship]);

        $command = new PlaceShip($game->id, $board->id, $ship->id, 2, 3, 'east');

        $placeShipHandler = new PlaceShipHandler($games, $boards, $ships);

        $placeShipHandler->handle($command);

        $placeShipHandler->handle($command);
    }

    public function test_it_places_ship_out_of_board(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $game = Game::startGame('game_id', 'board_id', 'other_board_id');

        $games = new InMemoryGameRepository([$game]);

        $board = $game->getBoards()['board_id'];
        $otherBoard = $game->getBoards()['other_board_id'];

        $boards = new InMemoryBoardRepository([$board, $otherBoard]);

        $ship = $board->getShips()->findFirst(function ($key, Ship $ship) {
            return $ship->size === 2;
        });

        $ships = new InMemoryShipRepository([$ship->id => $ship]);

        $command = new PlaceShip($game->id, $board->id, $ship->id, 9, 9, 'east');

        $placeShipHandler = new PlaceShipHandler($games, $boards, $ships);

        $placeShipHandler->handle($command);
    }

    public function test_it_places_ship_over_ship(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $game = Game::startGame('game_id', 'board_id', 'other_board_id');

        $games = new InMemoryGameRepository([$game]);

        $board = $game->getBoards()['board_id'];
        $otherBoard = $game->getBoards()['other_board_id'];

        $boards = new InMemoryBoardRepository([$board, $otherBoard]);

        $ship1 = $board->getShips()->findFirst(function ($key, Ship $ship) {
            return $ship->size === 2;
        });

        $ship2 = $board->getShips()->findFirst(function ($key, Ship $ship) {
            return $ship->size === 3;
        });

        $ships = new InMemoryShipRepository([$ship1->id => $ship1, $ship2->id => $ship2]);

        $command1 = new PlaceShip($game->id, $board->id, $ship1->id, 0, 0, 'east');
        $command2 = new PlaceShip($game->id, $board->id, $ship2->id, 0, 1, 'east');

        $placeShipHandler = new PlaceShipHandler($games, $boards, $ships);

        $placeShipHandler->handle($command1);
        $placeShipHandler->handle($command2);
    }
}
