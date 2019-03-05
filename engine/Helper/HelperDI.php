<?php
namespace Engine\Helper;
/**
 * Class HelperDI
 * @package Engine\Helper
 */
class HelperDI
{
    /**
     * @return \Engine\DI\DI
     */
    public static function di()
    {
        global $di;
        return $di;
    }
}