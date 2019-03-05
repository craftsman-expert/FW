<?php


namespace Cms\Model\Customer;


use Engine\AbstractModel;

/**
 * Class CustomerLog
 * @package Cms\Model\Customer
 */
class CustomerLog extends AbstractModel
{
    /**
     * @param array $data
     *
     * @throws \Engine\Core\Database\ExceptionSafeMySQL
     */
    public function log(array $data)
    {
        $this->db->query("insert into customer_log set ?u", $data);
    }
}