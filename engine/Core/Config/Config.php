<?php


namespace Engine\Core\Config;


use Exception;

class Config
{
    /**
     * Retrieves a config item.
     * @param  string $key
     * @param  string $group
     * @return mixed
     * @throws Exception
     */
    public static function item($key, $group = 'main')
    {
        if (!Repository::retrieve($group, $key)) {
            self::file($group);
        }
        return Repository::retrieve($group, $key);
    }



    /**
     * Retrieves a group config items.
     * @param  string $group The item group.
     * @return mixed
     * @throws Exception
     */
    public static function group($group)
    {
        if (!Repository::retrieveGroup($group)) {
            self::file($group);
        }
        return Repository::retrieveGroup($group);
    }



    /**
     * @param string $group
     * @return bool
     * @throws Exception
     */
    public static function file($group = 'main')
    {
        $path = path('config') . DS . $group . '.php';

        if (file_exists($path)) {


            $content = include $path;

            if (is_array($content)) {

                foreach ($content as $key => $value) {
                    Repository::store($group, $key, $value);
                }

                return true;
            } else {
                throw new Exception(sprintf('Config file <strong>%s</strong> is not a valid array.', $path));
            }
        } else {
            throw new Exception(sprintf('Cannot load config from file, file <strong>%s</strong> does not exist.', $path));
        }

        /** @noinspection PhpUnreachableStatementInspection */
        return false;
    }



    /**
     * @return string
     * @throws Exception
     */
    public static function getImageDir()
    {
        return  self::item('DIR_IMAGE');
    }
}