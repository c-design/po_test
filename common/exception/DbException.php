<?php

namespace common\exception;

class DbException extends \RuntimeException
{
    private array $errors;

    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null, array $errors = [])
    {
        $this->errors = $errors;
        parent::__construct($message, $code, $previous);
    }
}