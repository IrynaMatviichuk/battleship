<?php

namespace App\Console\Commands;

use Battleship\Application\StartGame as StartGameCommand;
use Battleship\Shared\CommandBus;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class StartGame extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function handle(CommandBus $commandBus): void
    {
        $command = new StartGameCommand(Str::uuid());

        $commandBus->handle($command);
    }
}
