<?php

namespace Tests\Battleship\Application;

use Battleship\Application\StartGame;
use Battleship\Application\StartGameHandler;
use Battleship\Infrastructure\InMemoryGameRepository;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class StartGameHandlerTest extends TestCase
{
    public function test_it_creates_boards(): void
    {
        $games = new InMemoryGameRepository([]);

        $gameId = Str::uuid();
        $boardId1 = Str::uuid();
        $boardId2 = Str::uuid();

        $command = new StartGame($gameId, [$boardId1, $boardId2]);

        $startGameHandler = new StartGameHandler($games);

        $startGameHandler->handle($command);

        $game = $games->findById($gameId);

        $this->assertEquals($gameId, $game->id);
    }
}
