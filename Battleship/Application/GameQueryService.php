<?php

namespace Battleship\Application;

use Battleship\QueryModel\BoardDto;
use Battleship\QueryModel\GameDto;
use Illuminate\Database\DatabaseManager;

class GameQueryService
{
    public function __construct(
        private readonly DatabaseManager $databaseManager,
    ) {}

    public function getGame(string $id): GameDto
    {
        $game = $this->databaseManager->table('games')->where('id', $id)->first();

        $boards = $this->databaseManager->table('boards')->where('game_id', $game->id)->get();

        $boardsDTOs = $boards->map(fn ($board) => new BoardDto($board->id, $board->size))->toArray();

        return new GameDto($game->id, $game->phase, $boardsDTOs);
    }
}
