<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class ProcessingPilotbookException extends Exception
{
    protected ?Throwable $throwable;

    /**
     * @param string $message
     * @param Throwable|null $throwable
     */
    public function __construct(string $message, ?Throwable $throwable = null)
    {
        parent::__construct($message);
        $this->throwable = $throwable;
    }

    public function context(): array
    {
        return [
          'exception' => $this->throwable
        ];
    }
}
