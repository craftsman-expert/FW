<?php


namespace Engine\Core\Setting;

use Engine\Core\Database\SafeMySQL;
use Engine\DI\DI;

/**
 * Class Setting
 * @package Engine\Core\Setting
 */
class Setting
{
    /**
     * @var SafeMySQL
     */
    private $db;



    /**
     * Setting constructor.
     *
     * @param di $di
     */
    public function __construct(DI $di)
    {
        $this->db = $di->get('db');
    }



    /**
     * @param      $key
     * @param bool $default
     *
     * @return mixed
     * @throws \Engine\Core\Database\ExceptionSafeMySQL
     */
    public function read($key, $default = false)
    {
        if (!Repository::get($key)) {
           Repository::set($key, $this->db->getOne("select `value` from setting where `key` = ?s", $key));
        }

        return Repository::has($key) ? Repository::get($key) : $default;
    }



    /**
     * @param string $key
     * @param        $value
     *
     * @throws \Engine\Core\Database\ExceptionSafeMySQL
     */
    public function write(string $key, $value)
    {
        $data = [
            'key' => $key,
            'value' => $value,
        ];

        $this->db->query("insert into setting set ?u on duplicate key update ?u", $data, $data);
    }
}