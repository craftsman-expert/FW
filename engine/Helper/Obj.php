<?php


namespace Engine\Helper;

use stdClass;

/**
 * Class Obj
 * @package Engine\Helper\Http
 */
class Obj
{
    private $object;



    /**
     * Obj constructor.
     *
     * @param string|stdClass $json
     */
    public function __construct($json)
    {
        if (is_object($json)){
            $this->object = $json;
        }else{
            $this->object = json_decode($json);
        }
    }



    /**
     * @param $key
     * @param bool $default
     * @return mixed
     */
    public function property($key, $default = false)
    {
        return isset($this->object->{$key}) ? $this->object->{$key}: $default;
    }
}