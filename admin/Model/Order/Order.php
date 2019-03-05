<?php


namespace Admin\Model\Order;

use Admin\Model\Sale\Sale;
use Engine\Core\Database\ExceptionSafeMySQL;
use Engine\Core\Database\SafeMySQL;

/**
 * Class Order
 * @package Admin\Model\Sale\Order
 */
class Order extends Sale
{


    /**
     * @param int $id
     *
     * @return SafeMySQL
     * @throws ExceptionSafeMySQL
     */
    public function delete(int $id)
    {
        return $this->db()->query("delete from `order` where  id = ?i", $id);
    }



    /**
     * @param int    $offset
     * @param int    $count
     * @param string $sort_snippet
     *
     * @return array
     * @throws ExceptionSafeMySQL
     */
    public function getRows(int $offset = 0, int $count = 10, $sort_snippet = ''): array
    {
        return $this->db()->getAll("select o.*, c.nickname as client from `order` o inner join customer c on o.customer_id = c.id ?p limit ?i, ?i",
            $sort_snippet,$offset, $count );
    }



    /**
     * @param int $id
     *
     * @return array
     * @throws ExceptionSafeMySQL
     */
    public function getRow(int $id): array
    {
        return $this->db()->getRow("select o.*, c.nickname, c.openid, c.phone from `order` o inner join customer c on o.customer_id = c.id and o.id = ?i limit 1", $id);
    }



    /**
     * @param int    $order_id
     * @param string $lang
     *
     * @return array
     * @throws ExceptionSafeMySQL
     */
    public function getProductRows(int $order_id, $lang = 'ru'): array
    {
        $language_id = $this->language->getId($lang);
        return $this->db()->getAll("select op.*, pd.title from `order` o inner join  order_product op on o.id = op.order_id inner join product_description pd on op.product_id = pd.product_id and pd.language_id = ?i and o.id = ?i", $language_id, $order_id);
    }




    /**
     * @return int
     * @throws ExceptionSafeMySQL
     */
    public function count(): int
    {
        return $this->db()->count('order');
    }
}