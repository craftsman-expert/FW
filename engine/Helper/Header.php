<?php

namespace Engine\Helper;


class Header
{


    /**
     * @param      $key
     * @param bool $default
     *
     * @return bool
     */
    public static function get($key, $default = false)
    {
        return self::has($key) ? getallheaders()[$key] : $default;
    }



    /**
     * @return array|false
     */
    public static function getAll()
    {
        return getallheaders();
    }



    /**
     * @param $key
     *
     * @return bool|string
     */
    public static function has($key)
    {
        return isset(getallheaders()[$key]) ? true : false;
    }



    /**
     * Contents of the User-Agent: header from the current request, if there is one.
     * This is a string denoting the user agent being which is accessing the page.
     * A typical example is: Mozilla/4.5 [en] (X11; U; Linux 2.2.9 i586).
     * Among other things, you can use this value with
     * @return string
     */
    public static function userAgent():string
    {
        return isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : 'No user agent!';
    }
}