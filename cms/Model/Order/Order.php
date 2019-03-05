<?php


namespace Cms\Model\Order;

use Cms\Model\CmsModel;
use Engine\Core\Database\ExceptionSafeMySQL;

/**
 * Class Order
 * @package Cms\Model\Order
 */
class Order extends CmsModel
{

    /**
     * @param int $customer_id
     *
     * @return int
     * @throws ExceptionSafeMySQL
     */
    public function create(int $customer_id): int
    {
        return (int)$this->db()->query("insert into `order` set customer_id = ?i", $customer_id)->insertId();
    }



    /**
     * @param array $data
     *
     * @return int
     * @throws ExceptionSafeMySQL
     */
    public function addProduct(array $data): int
    {
        return (int)$this->db()->query("insert into order_product set ?u", $data)->insertId();
    }



    /**
     * @param int   $id
     * @param float $amount
     *
     * @throws ExceptionSafeMySQL
     */
    public function setAmount(int $id, float $amount): void
    {
        $this->db()->query("update `order` set amount = ?s where id = ?i", $amount, $id);
    }



    /**
     * @param int $customer_id
     * @param int $offset
     * @param int $count
     *
     * @return array
     * @throws ExceptionSafeMySQL
     */
    public function getRows(int $customer_id, int $offset = 0, int $count = 10): array
    {
        return $this->db->getAll("select * from `order` where customer_id = ?i limit ?i, ?i",$customer_id, $offset, $count);
    }
}