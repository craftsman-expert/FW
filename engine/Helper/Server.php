<?php


namespace Engine\Helper;

/**
 * Class Server
 * @package Engine\Helper
 */
class Server
{
    /**
     *  Заголовок host из текущего запроса, если он есть.
     * @return string|null
     */
    public static function getDomain()
    {
        return isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null;
    }



    /**
     * @return null|string
     */
    public static function getServerName()
    {
        return isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : null;
    }



    /**
     * @return mixed
     */
    public static function getSubDomain()
    {
        $re = '/[^\.]*/';
        preg_match($re, Server::getDomain(), $matches);
        return $matches[0];
    }



    /**
     * Client IP address
     * @return string
     */
    public static function remoteAddress():string
    {
        return $_SERVER['REMOTE_ADDR'];
    }



    /**
     * @return mixed
     */
    static function getRequestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }



    /**
     * @return mixed
     */
    static function getRequestURI()
    {
        return $_SERVER['REQUEST_URI'];
    }



    /**
     * @return mixed
     */
    static function getQueryString()
    {
        return $_SERVER['QUERY_STRING'];
    }

}