<?php


namespace Admin\Model\User;


use Admin\Model\AdminAbstractModel;
use Admin\Object\User\UserInfo;

/**
 * Class Auth
 * @package Admin\Model\Auth
 */
class User extends AdminAbstractModel
{
    /**
     * @param string $access_token
     * @return array|boolean
     */
    public function getUser(string $access_token)
    {


        return isset($user) ? $user : false;
    }



    /**
     * @param int $user_id
     * @return array|bool|FALSE
     */
    public function getUserInfo(int $user_id)
    {
        $w = array();
        $w[] = $this->db->parse(" u.id = ?i", $user_id);
        $where = "WHERE ".implode('',$w);

        $user_info = $this->db->getRow("SELECT * FROM user u ?p", $where);

        return (!empty($user_info)) ? $user_info : false;
    }



    /**
     * @param string $access_token
     * @return bool
     */
    public function verification(string $access_token): bool
    {
        $sql = $this->db->parse(
            "select count(*) from `user` as u inner join user_session as us on u.id = us.user_id and us.access_token = ?s limit 1", $access_token);

        $key = md5($sql);

        $value = $this->memcache->get($key);

        if ($value) {
            return $value;
        }

        $value = (bool)$this->db->getOne($sql);

        if ($value) {
            $this->memcache->set($key, $value, null, time() + 100); // expire 5 min
            return $value;
        }

        return $value;
    }



    /**
     * @param string $access_token
     * @return int
     */
    public function getUserId(string $access_token): int
    {
        return (int)$this->db->getOne("select u.id from user u inner join user_session us on u.id = us.user_id and us.access_token = ?s limit 1", $access_token);
    }
}