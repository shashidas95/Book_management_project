<?php

namespace App\Exceptions;

use Exception;

class BookNotAvailableException extends Exception
{
   protected $message = "Book is not found for borrowing";
   protected $status = 404;
}
