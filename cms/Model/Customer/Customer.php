<?php


namespace Cms\Model\Customer;


use Cms\Model\CmsModel;
use Engine\Core\Database\ExceptionSafeMySQL;

/**
 * Class Customer
 * @package Cms\Model\Customer
 */
class Customer extends CmsModel
{


    /**
     * @param string $openid
     * @param bool   $phone
     *
     * @return int
     * @throws ExceptionSafeMySQL
     */
    public function getId(string $openid, $phone = false): int
    {
        $or = [];
        $or[] = $this->db()->parse(" openid = ?s", $phone);

        if ($phone){
            $or[] = $this->db()->parse(" or phone = ?s", $phone);
        }

        $where = "where " . implode(' ', $or);

        return (int)$this->db()->getOne("select id from customer ?p", $where);
    }



    /**
     * Add new customer
     *
     * @param array $data
     *
     * @return int
     * @throws ExceptionSafeMySQL
     */
    public function add(array $data): int
    {
        $this->db()->query("insert into customer set ?u", $data);
        return $this->db()->insertId();
    }



    /**
     * @param string $phone
     *
     * @return int
     * @throws ExceptionSafeMySQL
     */
    public function hasPhone(string $phone): int
    {
        return (bool)$this->db()->getOne("select count(phone) from customer where phone = ?s", trim($phone));
    }



    /**
     * @param int $customer_id
     *
     * @return array|FALSE
     * @throws ExceptionSafeMySQL
     */
    public function get(int $customer_id)
    {
        return $this->db->getRow("select * from customer where id = ?i", $customer_id);
    }



    /**
     * @param int   $customer_id
     * @param array $data
     *
     * @throws ExceptionSafeMySQL
     */
    public function update(int $customer_id, array $data)
    {
        $this->db()->query("update customer set ?u where id = ?i", $data, $customer_id);
    }


    /**
     * @param int $id
     *
     * @throws ExceptionSafeMySQL
     */
    public function delete(int $id): void
    {
        $this->db()->query("delete from customer where id = ?i", $id);
    }



    /**
     * @param string $openid
     *
     * @return bool
     * @throws ExceptionSafeMySQL
     */
    public function hasOpenId(string $openid): bool
    {
        return (bool)$this->db()->getOne("select count(*) from customer where openid = ?s", $openid);
    }



    /**
     * @param int    $customer_id
     * @param string $access_token
     * @param string $hash_user_agent
     * @param string $ip
     *
     * @return int
     * @throws ExceptionSafeMySQL
     */
    public function setSession(int $customer_id, string $access_token, string $hash_user_agent, string $ip): int
    {
        $data = [
            'customer_id' => $customer_id,
            'access_token' => $access_token,
            'hash_user_agent' => $hash_user_agent,
            'ip' => $ip,
        ];
        $this->db()->query("insert into customer_session set ?u", $data);

        return $this->db()->insertId();
    }



    /**
     * @param int $id
     *
     * @return array|FALSE
     * @throws ExceptionSafeMySQL
     */
    public function getSession(int $id)
    {
        return $this->db()->getRow("select * from customer_session where id = ?i", $id);
    }



    /**
     * @param int $customer_id
     *
     * @throws ExceptionSafeMySQL
     */
    public function resetSession(int $customer_id): void
    {
        $this->db()->query("delete from customer_session where customer_id = ?i", $customer_id);
    }



    /**
     * @param int $id
     * @param int $expires_in
     *
     * @throws ExceptionSafeMySQL
     */
    public function setExpires(int $id, $expires_in = 3600)
    {
        $this->db()->query("update customer_session set expires_in = ?i where id = ?i", $expires_in, $id);
    }
}