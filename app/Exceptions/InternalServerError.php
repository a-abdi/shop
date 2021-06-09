<?php

namespace App\Exceptions;

use Exception;
use App\Traits\ApiResponser;

class InternalServerError extends Exception
{
    use ApiResponser;

    public function __construct(protected $message = "internal server error"){}

    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        return true;
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return $this->errorResponse($this->message, 500);
    }
}
