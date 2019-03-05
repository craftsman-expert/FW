<?php


namespace Engine\Helper;


/**
 * time()+60*60*24*30 = 30 days
 * Class Cookie
 * @package Engine\Helper
 */
class Cookie
{

    /**
     * @return array
     */
    public static function getAll(): array
    {
        return $_COOKIE;
    }

    /**
     * @param $key
     * @param $value
     * @param int $time
     * @param string $path
     */
    public static function set($key, $value, $path = '/', $time = 86400)
    {
        setcookie($key, $value, time() + $time, $path);
    }



    /**
     * @param $key
     * @param bool $default
     * @return null
     */
    public static function get($key, $default = false)
    {
        if (isset($_COOKIE[$key]) && !empty($_COOKIE[$key])) {
            return $_COOKIE[$key];
        }
        return $default;
    }



    /**
     * @param $key
     * @return bool
     */
    public static function has($key)
    {
        if (isset($_COOKIE[$key])) {
            return true;
        } else {
            return false;
        }
    }



    /**
     * @param $key
     * @param string $path
     */
    public static function delete($key, $path = '/')
    {
        if (isset($_COOKIE[$key])) {
            self::set($key, '', $path, -3600);
            unset($_COOKIE[$key]);
        }
    }
}