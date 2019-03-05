<?php


namespace Engine\Core\Exception;


use Exception;

/**
 * Class ExceptionCMS
 * @package Engine\Core\Exception
 */
class ExceptionCMS extends Exception
{

    protected $title;
    protected $type;

    /**
     * ExceptionCMS constructor.
     * @param $title
     * @param $message
     * @param string $type
     * @param int $code
     */
    public function __construct ($title, $message, $type = '', int $code = -1)
    {
        parent::__construct($message, $code, null);
        $this->title = $title;
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getTitle ()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getType ()
    {
        return $this->type;
    }


}