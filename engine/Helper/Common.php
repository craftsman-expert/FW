<?php

namespace Engine\Helper;

use DateTime;
use Engine\Core\Router\DispatchedRoute;

class Common
{


    /**
     * @return bool
     */
    static function isPost()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            return true;
        }
        return false;
    }



    /**
     * @return mixed
     */
    static function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }



    /**
     * @return bool|string
     */
    static function getPathUrl()
    {
        $pathUrl = $_SERVER['REQUEST_URI'];
        if ($position = strpos($pathUrl, '?')) {
            $pathUrl = substr($pathUrl, 0, $position);
        }
        return $pathUrl;
    }



    /**
     * Возвращает "PathUrl" в стиле CamelCase
     * @return string
     */
    static function getPathUrlCamelCase()
    {
        $pathUrl = $_SERVER['REQUEST_URI'];
        if ($position = strpos($pathUrl, '?')) {
            $pathUrl = substr($pathUrl, 0, $position);
        }
        return str_replace(' ', '', ucwords(str_replace('/', ' ', $pathUrl)));
    }



    /**
     * @param string $delimiter
     *
     * @return string
     */
    static function getPath($delimiter = '_')
    {
        $pathUrl = $_SERVER['REQUEST_URI'];
        if ($position = strpos($pathUrl, '?')) {
            $pathUrl = substr($pathUrl, 0, $position);
        }

        $re = "/^\\$delimiter/";
        $pathUrl = str_replace('.', '_', ucwords(str_replace('/', $delimiter, $pathUrl)));
        return preg_replace($re, '', $pathUrl);
    }



    /**
     * Получить метод вызываемого контроллера <hr>
     * @return string
     */
    static function getControllerMethod()
    {
        $routerDispatch = HelperDI::di()->get('router')->dispatch(Common::getMethod(), Common::getPathUrl());
        list($class, $action) = explode(':', $routerDispatch->getController(), 2);
        return $action;
    }



    /**
     * @return mixed
     */
    static function getController()
    {
        $routerDispatch = HelperDI::di()->get('router')->dispatch(Common::getMethod(), Common::getPathUrl());
        return $routerDispatch->getController();
    }



    /**
     * Returns the type of pattern
     * If unsuccessful, returns a logical false.
     *
     * @param string $pathUrl
     *
     * @return string|boolean
     */
    static function getRouteType(string $pathUrl)
    {
        $routes = (array) HelperDI::di()->get('router')->getRoutes();
        $pattern = (string) $routes[str_ireplace('/', '.', $pathUrl)]['type'];
        return !empty($pattern) ? $pattern : false;
    }



    /**
     * Return current pattern type
     * @return bool|string
     */
    static function getCurrentPatternType()
    {
        $routes = (array) HelperDI::di()->get('router')->getRoutes();
        $pattern = (string) $routes[str_replace('_', '.', self::getPath("."))]['type'];
        return !empty($pattern) ? $pattern : false;
    }




    /**
     * @param string $controller
     * @param array  $parameters
     * @param bool   $dir
     */
    static function callController(string $controller, $parameters = [], $dir = false)
    {
        $routerDispatch = new DispatchedRoute($controller, $parameters, $dir);

        list($class, $action) = explode(':', $routerDispatch->getController(), 2);
        $controller = '\\' . ENV . '\\Controller\\' . $class;
        $parameters = $routerDispatch->getParameters();
        call_user_func_array([new $controller(di::di()), $action], $parameters);
    }



    /**
     * @param string $key
     *
     * @return bool
     */
    static function isLinkActive($key)
    {
        if (self::searchMatchString($_SERVER['REQUEST_URI'], $key)) {
            return true;
        }
        return false;
    }



    /**
     * @param string $string
     * @param string $find
     *
     * @return bool
     */
    function searchMatchString($string, $find)
    {
        if (strripos($string, $find) !== false) {
            return true;
        }
        return false;
    }



    /**
     * Сравнивание дат
     *
     * @param        $sDate1
     * @param        $sDate2
     * @param string $sUnit
     *
     * @return false|float|int
     */
    static function DateDiffInterval($sDate1, $sDate2, $sUnit = 'H')
    {
        //subtract $sDate2-$sDate1 and return the difference in $sUnit (Days,Hours,Minutes,Seconds)
        $nInterval = strtotime($sDate2) - strtotime($sDate1);
        if ($sUnit == 'D') { // days
            $nInterval = $nInterval / 60 / 60 / 24;
        } else if ($sUnit == 'H') { // hours
            $nInterval = $nInterval / 60 / 60;
        } else if ($sUnit == 'M') { // minutes
            $nInterval = $nInterval / 60;
        } else if ($sUnit == 'S') { // seconds
        }
        return $nInterval;
    } //DateDiffInterval



    /**
     * Проверка, является ли строка датой
     *
     * @param        $date
     * @param string $format
     *
     * @return bool
     */
    function is_date($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }



}