<?php

namespace App\Exception;

class ValidationException extends \Exception
{
    public function __construct(array $data = [])
    {
        $message = json_encode(['errors' => $data]);
        parent::__construct($message, 422);
    }
}