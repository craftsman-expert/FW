<?php

namespace Engine\Core\Database;


use Exception;
use Throwable;

/**
 * Class ExceptSafeMySQL
 * @package Engine\Core\Database
 */
class ExceptionSafeMySQL extends Exception
{
    /**
     *
     * ExceptSafeMySQL constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct( $message = "",  $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}