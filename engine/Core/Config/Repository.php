<?php

namespace Engine\Core\Config;


/**
 * Class Repository
 * @package Engine\Core\Config
 */
class Repository
{
    /**
     * @var array Stored config items.
     */
    protected static $stored = [];



    /**
     * Stores a config item.
     * @param  string $group The item group.
     * @param  string $key   The item key.
     * @param  mixed $data   The item data.
     * @return void
     */
    public static function store($group, $key, $data)
    {
        // Ensure the group is a valid array.
        if (!isset(static::$stored[$group]) || !is_array(static::$stored[$group])) {
            static::$stored[$group] = [];
        }
        // Store the data.
        static::$stored[$group][$key] = $data;
    }



    /**
     * Retrieves a config item.
     *
     * @param  string $group The item group.
     * @param  string $key   The item key.
     * @param bool|mixed    $default
     *
     * @return mixed
     */
    public static function retrieve($group, $key, $default = false)
    {
        return isset(static::$stored[$group][$key]) ? static::$stored[$group][$key] : $default;
    }



    /**
     * Retrieves a config item.
     *
     * @param  string $group The item group.
     * @param bool    $default
     *
     * @return mixed
     */
    public static function retrieveGroup($group, $default = false)
    {
        return isset(static::$stored[$group]) ? static::$stored[$group] : $default;
    }
}