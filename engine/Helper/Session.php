<?php


namespace Engine\Helper;

use SessionHandler;

/**
 * Class Session
 * @package Engine\Helper
 */
class Session
{
    /**
     * @return string
     */
    static public function sid(): string
    {
        return session_id();
    }



    /**
     * @param string $prefix
     *
     * @return string
     */
    static public function create_sid(string $prefix): string
    {
        return session_create_id($prefix);
    }



    /**
     * @param string $key
     *
     * @return bool
     */
    static public function has(string $key): bool
    {
        if (isset($_SESSION[$key])){
            return true;
        }

        return false;
    }



    /**
     * @param string $key
     * @param bool   $default
     *
     * @return mixed
     */
    static public function read(string $key, $default = false)
    {
        if (self::has($key)){
            return $_SESSION[$key];
        }

        return $default;
    }



    /**
     * @param string $key
     * @param        $value
     */
    static public function write(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

}