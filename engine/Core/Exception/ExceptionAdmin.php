<?php


namespace Engine\Core\Exception;


use Exception;
use Throwable;

/**
 * Class ExceptionAdmin
 * @package Engine\Core\Exception
 */
class ExceptionAdmin extends Exception
{

    protected $message;
    protected $code;
    protected $title;
    protected $type;



    /**
     * ExceptionAdmin constructor.
     * @param $title
     * @param string $message
     * @param string $type
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct ($title, $message, $type = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->type = $type;
        $this->title = $title;
    }



    /**
     * @return string
     */
    public function getType (): string
    {
        return $this->type;
    }



    /**
     * @return string
     */
    public function getTitle (): string
    {
        return $this->title;
    }
}