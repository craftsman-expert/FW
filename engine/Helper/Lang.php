<?php

namespace Engine\Helper;
/**
 * Class Lang
 * @package Engine\Helper
 */
class Lang
{
    public static function l($section, $key, $name)
    {
        $language = HelperDI::di()->get('language');
        return isset($language->{$section}[$key][$name]) ? $language->{$section}[$key][$name] : '???';
    }



    /**
     * @return bool
     */
    public static function lang()
    {
        return HelperDI::di()->get('language');
    }



    /**
     * @return bool
     */
    public static function instance()
    {
        return HelperDI::di()->get('language');
    }




    /**
     * @param string $section
     * @param string $key
     * @return void
     */
    public static function _e($section, $key)
    {
        $language = HelperDI::di()->get('language');
        echo isset($language->{$section}[$key]) ? $language->{$section}[$key] : '';
    }
}