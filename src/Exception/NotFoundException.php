<?php

namespace App\Exception;

class NotFoundException extends \Exception
{
    /**
     * @param string|null $message
     */
    public function __construct(?string $message = null)
    {
        if($message === null){
            $message = "Aranan nesne veritabanında mevcut değil.";
        }
        parent::__construct($message, 404);
    }
}