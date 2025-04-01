<?php

namespace App\Console\Commands;

use Battleship\Application\PlaceShip as PlaceShipCommand;
use Battleship\Shared\CommandBus;
use Illuminate\Console\Command;

class PlaceShip extends  Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:place-ship {boardId} {shipId} {row} {column} {direction}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function handle(CommandBus $commandBus): void
    {
        $boardId = $this->argument('boardId');
        $shipId = $this->argument('shipId');
        $row = (int)$this->argument('row');
        $column = (int)$this->argument('column');
        $direction = $this->argument('direction');

        $command = new PlaceShipCommand($boardId, $shipId, $row, $column, $direction);

        $commandBus->handle($command);
    }
}
