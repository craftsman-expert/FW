<?php

namespace Engine\Helper;

/**
 * Class Network
 * @package Engine\Helper
 */
class Network
{
    /**
     * @param $url
     */
    static function location($url)
    {
        header("Location: $url");
        exit;
    }

}