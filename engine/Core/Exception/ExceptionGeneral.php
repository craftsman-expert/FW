<?php


namespace Engine\Core\Exception;


use Exception;

/**
 * Class ExceptionGeneral
 * @package Engine\Core\Exception
 */
class ExceptionGeneral extends Exception
{
    /**
     * ExceptionGeneral constructor.
     *
     * @param        $title
     * @param        $message
     * @param string $level
     * @param int    $code
     */
    public function __construct($title, $message, $level = '', int $code = -1)
    {
        parent::__construct($message, $code);
    }
}