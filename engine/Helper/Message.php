<?php

namespace Engine\Helper;
use Engine\Core\LPManager\LPManager;
use stdClass;

/**
 * Class Message
 * @package Engine\Helper
 */
class Message
{


    /**
     * @param $message
     * @param int $code
     * @param null $data
     * @param bool $echo
     * @return stdClass
     */
    public static function success($message, $code = 0, $data = null, $echo = true)
    {
        header("Content-type: application/json; charset=utf-8");

        $obj = new stdClass();
        $obj->error_code = $code;
        $obj->type = 'success';
        $obj->title = self::lpm()->translate('message', 'success');
        $obj->msg = $message;

        if (isset($data)){
            $obj->data = $data;
        }

        if ($echo) {
            echo json_encode($obj, JSON_UNESCAPED_UNICODE);
            exit();
        }

        return $obj;
    }



    /**
     * @param $title
     * @param $message
     * @param int $code
     */
    public static function info($title, $message, $code = -1)
    {
        header("Content-type: application/json; charset=utf-8");
        echo json_encode((object)
        [
            'error_code' => $code,
            'type'   => 'info',
            'title'  => self::lpm()->translate('message', 'info'),
            'msg'       => $message
        ],
            JSON_UNESCAPED_UNICODE);

    }



    /**
     * @param $message
     * @param int $code
     * @param bool $echo
     * @return stdClass
     */
    public static function error($message, $code = -1, $echo = true)
    {
        $obj = new stdClass();
        $obj->error_code = $code;
        $obj->type = 'error';
        $obj->title = self::lpm()->translate('message', 'error');
        $obj->msg = $message;

        if (isset($data)){
            $obj->data = $data;
        }

        if ($echo) {
            header("Content-type: application/json; charset=utf-8");
            echo json_encode($obj, JSON_UNESCAPED_UNICODE);
            exit();
        }

        return $obj;
    }



    /**
     * @param $message
     * @param int $code
     * @param null $data
     * @param bool $echo
     * @return stdClass
     */
    public static function unknownError($message, $code = -1, $data = null, $echo = true)
    {
        $obj = new stdClass();
        $obj->error_code = $code;
        $obj->type = 'e';
        $obj->title = self::lpm()->translate('message', 'unknown_error');
        $obj->msg = $message;

        if (isset($data)){
            $obj->data = $data;
        }

        if ($echo) {
            header("Content-type: application/json; charset=utf-8");
            echo json_encode($obj, JSON_UNESCAPED_UNICODE);
            exit();
        }

        return $obj;
    }



    /**
     * @param $message
     * @param int $code
     * @param null $data
     * @param bool $echo
     * @return stdClass
     */
    public static function warning($message, $code = -1, $data = null, $echo = true)
    {
        $obj = new stdClass();
        $obj->error_code = $code;
        $obj->type = 'warning';
        $obj->title = self::lpm()->translate('message', 'warning');
        $obj->msg = $message;

        if (isset($data)){
            $obj->data = $data;
        }

        if ($echo) {
            header("Content-type: application/json; charset=utf-8");
            echo json_encode($obj, JSON_UNESCAPED_UNICODE);
            exit();
        }

        return $obj;
    }



    /**
     * @return bool|LPManager
     */
    private static function lpm()
    {
        return HelperDI::di()->get('lpm');
    }

}