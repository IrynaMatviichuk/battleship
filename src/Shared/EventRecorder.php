<?php

namespace Battleship\Shared;

trait EventRecorder
{
    /**
     * @var object[]
     */
    private array $messages = [];

    /**
     * @return object[]
     */
    public function recordedMessages(): array
    {
        return $this->messages;
    }

    public function eraseMessages(): void
    {
        $this->messages = [];
    }

    /**
     * Record a message.
     */
    protected function record(object $message): void
    {
        $this->messages[] = $message;
    }
}
