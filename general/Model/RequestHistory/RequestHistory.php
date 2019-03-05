<?php


namespace General\Model\RequestHistory;

use Engine\AbstractModel;

/**
 * Class RequestHistory
 * @package General\Model\RequestHistory
 */
class RequestHistory extends AbstractModel
{
    /**
     * @param string $app_session_id
     * @param string $request_uri
     * @param string $type
     * @param string $method
     *
     * @throws \Engine\Core\Database\ExceptionSafeMySQL
     */
    public function add($app_session_id, $request_uri, $type, $method = 'Unknown')
    {
        $this->db->query("insert into request_history set app_session_id = ?s, request_uri = ?s, type = ?s, method = ?s", $app_session_id, $request_uri, $type, $method);
    }



    /**
     * @param string $app_session_id
     *
     * @return array|FALSE
     * @throws \Engine\Core\Database\ExceptionSafeMySQL
     */
    public function get(string $app_session_id)
    {
        return $this->db->getRow("select * from request_history where app_session_id = ?s", $app_session_id);
    }



    /**
     * @param string $app_session_id
     * @param string $type
     *
     * @return FALSE|string
     * @throws \Engine\Core\Database\ExceptionSafeMySQL
     */
    public function getLast(string $app_session_id, string $type = 'PAGE')
    {
        return $this->db->getOne("select request_uri from request_history where app_session_id = ?s and create_at = (select max(create_at) from request_history where app_session_id = ?s AND type = ?s) LIMIT 1", $app_session_id, $app_session_id, $type);
    }
}