<?php

namespace App\Http\Controllers;

use Battleship\Application\GameQueryService;
use Illuminate\Http\JsonResponse;

class GameController extends Controller
{
    public function __construct(private GameQueryService $gameQueryService)
    {
    }

    public function show(string $id): JsonResponse
    {
        $board = $this->gameQueryService->getGame($id);

        return new JsonResponse($board);
    }
}
