<?php


namespace Engine\Core\Setting;

/**
 * Class Repository
 * @package Engine\Core\Setting
 */
class Repository
{
    /**
     * @var array Stored setting items.
     */
    protected static $store = [];



    /**
     * Stores a setting item.
     *
     * @param $key
     * @param $value
     *
     * @return void
     */
    public static function set($key, $value)
    {
        if (!isset(static::$store[$key])) {
            static::$store[$key] = [];
        }
        static::$store[$key] = $value;
    }



    /**
     * Retrieves a setting item.
     *
     * @param  string $key The item key.
     *
     * @return mixed
     */
    public static function get($key)
    {
        return isset(static::$store[$key]) ? static::$store[$key] : false;
    }



    /**
     * @param $key
     *
     * @return bool
     */
    public static function has($key):bool
    {
        return isset(static::$store[$key]) ? true : false;
    }


}