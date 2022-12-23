<?php

namespace App\Exceptions;

use Exception;

class ProcessingPilotbookJobException extends Exception
{
    protected string $exec;

    /**
     * @param string $exec
     * @param $message
     */
    public function __construct(string $exec, $message)
    {
        $this->exec = $exec;
        parent::__construct($message);
    }

    public function getExec(): string
    {
        return $this->exec;
    }
}
