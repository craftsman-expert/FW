<?php

namespace Engine\Helper;

/**
 * Class Helper
 * @package Engine\Helper
 */
class Helper
{
    /**
     * @param $obj
     * @param int $options
     * @param int $depth
     */
    public static function echoJsonUtf8($obj, $options = JSON_UNESCAPED_UNICODE, $depth = 512)
    {
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($obj, $options, $depth);
    }



    /**
     * @param     $obj
     * @param int $options
     * @param int $depth
     */
    public static function echoJavascriptUtf8($obj, $options = JSON_UNESCAPED_UNICODE, $depth = 512)
    {
        header('Content-type: application/json; application/javascript');
        echo json_encode($obj, $options, $depth);
    }
}