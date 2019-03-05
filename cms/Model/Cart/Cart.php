<?php


namespace Cms\Model\Cart;


use Cms\Model\CmsModel;
use Engine\Core\Database\ExceptionSafeMySQL;

/**
 * Class Cart
 * @package Cms\Model\Cart
 */
class Cart extends CmsModel
{

    /**
     * @param array $data [product_id, customer_id, session_id]
     *
     * @throws ExceptionSafeMySQL
     */
    public function add(array $data)
    {
        $product = $this->db()->getRow("select * from product where id = ?i", $data['product_id']);

        $sql = "insert into cart set ?u, price = ?s, quantity = 1 on duplicate key update ?u, quantity = quantity + 1";
        $this->db()->query($sql,
            // insert
            $data,
            $product['price'],

            // update
            $data
        );
    }



    /**
     * @param string $app_session_id
     *
     * @return array
     * @throws ExceptionSafeMySQL
     */
    public function get(string $app_session_id)
    {
        $fields = "c.*, p.article, p.price, p.minimum";
        $sql = "select ?p from cart c inner join product p on c.product_id = p.id and c.app_session_id = ?s";
        return $this->db()->getAll($sql, $fields, $app_session_id);
    }



    /**
     * @param int $id
     *
     * @throws ExceptionSafeMySQL
     */
    public function remove(int $id): void
    {
        $this->db()->query("delete from cart where id = ?i", $id);
    }



    /**
     * @param int $id
     *
     * @throws ExceptionSafeMySQL
     */
    public function quantityInc(int $id): void
    {
        $this->db()->query("update cart set quantity = quantity + 1 where id = ?i", $id);
    }



    /**
     * @param int $id
     *
     * @throws ExceptionSafeMySQL
     */
    public function quantityDec(int $id): void
    {
        $this->db()->query("update cart set quantity = quantity - 1 where id = ?i", $id);
    }





    /**
     * @param string $app_session_id
     * @param int    $customer_id
     * @param string $lang
     *
     * @return array
     * @throws ExceptionSafeMySQL
     */
    public function getCheckouts(string $app_session_id, $customer_id = 0, string $lang = 'zh')
    {
        $language_id = $this->language->getId($lang);
        $sql = "select c.id, c.product_id, c.create_at, c.quantity, c.price, c.amount, p.image, pd.title from cart c inner join product p on c.product_id = p.id inner join product_description pd on p.id = pd.product_id and pd.language_id = ?i where c.app_session_id = ?s";
        return $this->db()->getAll($sql, $language_id, $app_session_id);
    }



    /**
     * Number of items in the cart
     *
     * @param string $app_session_id
     *
     * @return int
     * @throws ExceptionSafeMySQL
     */
    public function count(string $app_session_id):int
    {
        $sql = "select count(*) from cart where app_session_id = ?s";
        return $this->db()->getOne($sql, $app_session_id);
    }
}