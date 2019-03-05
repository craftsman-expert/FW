<?php

use Engine\Core\Setting\Setting;



/**
 * Returns path to a Flexi CMS folder.
 *
 * @param  string $section
 * @return string
 */
function path($section)
{
    $pathMask = ROOT_DIR . DS . '%s';

    switch (ENV){
        case 'Admin': $pathMask = ROOT_DIR . DS . strtolower(ENV). DS . '%s';
            break;
        case 'Cms': $pathMask = ROOT_DIR . DS . strtolower(ENV). DS . '%s';
            break;
        case 'Api': $pathMask = ROOT_DIR . DS . strtolower(ENV) . DS . '%s';
            break;
        case 'General': $pathMask = ROOT_DIR . DS . strtolower(ENV). DS . '%s';
            break;

    }

    // Return path to correct section.
    switch (strtolower($section))
    {
        case 'controller':
            return sprintf($pathMask, 'Controller');
        case 'config':
            return sprintf($pathMask, 'Config');
        case 'model':
            return sprintf($pathMask, 'Model');
        case 'view':
            return sprintf($pathMask, 'View');
        case 'language':{
            switch (ENV){
                case 'Admin':{
                    return sprintf($pathMask, 'Language');
                }
                case 'Cms':{
                    return sprintf(ROOT_DIR.'/content/themes/%s/language', Setting::get('active_theme'));
                }
            }
        }
        default:
            return ROOT_DIR;
    }
}



/**
 * @param string $section
 * @return string
 */
function path_content($section = '')
{
    $pathMask = $_SERVER['DOCUMENT_ROOT'] . DS . 'content' . DS . '%s';
    // Return path to correct section.
    switch (strtolower($section))
    {
        case 'themes':
            return sprintf($pathMask, 'themes');
        case 'plugins':
            return sprintf($pathMask, 'plugins');
        case 'uploads':
            return sprintf($pathMask, 'uploads');
        default:
            return $_SERVER['DOCUMENT_ROOT'] . DS . 'content';
    }
}



/**
 * Получить директорию окружения Admin
 * @param string $section
 * @return string
 */
function path_admin($section = '')
{
    $pathMask = $_SERVER['DOCUMENT_ROOT'] . DS . 'admin/%s';

    switch (strtolower($section))
    {
        case 'plugins':
            return sprintf($pathMask, 'Plugins');
        default:
            return $_SERVER['DOCUMENT_ROOT'] . DS . 'admin';
    }
}



/**
 * Returns list languages
 *
 * @return array
 */
function languages()
{
    $directory = path('language');
    $list      = scandir($directory);
    $languages = [];
    if (!empty($list)) {
        unset($list[0]);
        unset($list[1]);
        foreach ($list as $dir) {
            $pathLangDir = $directory . DS . $dir;
            $pathConfig  = $pathLangDir . '/config.json';
            if (is_dir($pathLangDir) and is_file($pathConfig)) {

                $config = file_get_contents($pathConfig);
                $info   = json_decode($config);
                $languages[] = $info;
            }
        }
    }
    return $languages;
}



/**
 * Return list themes.
 *
 * @return array
 * @throws Exception
 */
function getThemes()
{
    $themesPath = '../content/themes';
    $list       = scandir($themesPath);
    $baseUrl    = Config::item('HTTP_CATALOG');
    $themes     = [];
    if (!empty($list)) {
        unset($list[0]);
        unset($list[1]);
        foreach ($list as $dir) {
            $pathThemeDir = $themesPath . '/' . $dir;
            $pathConfig   = $pathThemeDir . '/theme.json';
            $pathScreen   = $baseUrl . '/content/themes/' . $dir . '/screen.jpg';
            if (is_dir($pathThemeDir) && is_file($pathConfig)) {
                $config = file_get_contents($pathConfig);
                $info   = json_decode($config);
                $info->screen   = $pathScreen;
                $info->dirTheme = $dir;
                $themes[] = $info;
            }
        }
    }
    return $themes;
}



/**
 * Return list plugins.
 * @param string $env
 * @return array
 */
function getPlugins($env = '')
{
    global $di;

    if ($env == '')
        $env = ENV;

    switch ($env){
        case 'Admin': $pluginsPath = path_admin('plugins');
            break;
        case 'Cms': $pluginsPath = path_content('plugins');
            break;
        default: $pluginsPath = path_content('plugins');
    }

    $list        = scandir($pluginsPath);
    $plugins     = [];
    if (!empty($list)) {
        unset($list[0]);
        unset($list[1]);
        foreach ($list as $namePlugin) {
            $namespace = '\\Plugin\\' . $namePlugin . '\\Plugin';
            if (class_exists($namespace)) {
                /** @var Engine\Plugin $plugin */
                $plugin = new $namespace($di);
                $plugins[$namePlugin] = $plugin->details();
            }
        }
    }
    return $plugins;
}


/**
 * Return list plugins.
 * @param $modelName
 * @return bool
 */
function getModel($modelName)
{
    global $di;
    return $di->get($modelName);
}



/**
 * @param string $file_name
 * @return string
 */
function extractFileExt(string  $file_name): string
{
    /**
     * Класс SplFileInfo предлагает высокоуровневый объектно-ориентированный интерфейс к информации для отдельного файла.
     */
    $info = new SplFileInfo($file_name);
    $ext = $info->getExtension();
    unset($info);
    return $ext;
}



/**
 * @param $var
 * @return bool
 */
function isBool($var) {
    if (!is_string($var))
        return (bool) $var;
    switch (strtolower($var)) {
        case 'true': return true;
        case 'false': return true;
        default:
            return false;
    }
}



/**
 * @param $var
 * @return bool|null
 */
function toBool($var) {
    if (!is_string($var))
        return (bool) $var;
    switch (strtolower($var)) {
        case 'true': return true;
        case 'false': return false;
        default:
            return null;
    }
}



/**
 *
 * @param $var
 * @return bool|float|int|null
 */
function varCast($var)
{
    if (is_numeric($var)){
        return (int)$var;
    } elseif (is_float($var)) {
        return (double)$var;
    } elseif (toBool($var)){
         return toBool($var);
    } else {
        return $var;
    }
}



/**
 *
 * @param array $array
 *
 * @return array
 */
function cast(array $array){
    $arr = [];
    foreach ($array as $key => $val){
        $arr[$key] =  varCast($val);
    }
    return $arr;
}



/**
 * @param string $string
 *
 * @return bool
 */
function json_validate(string $string): bool
{
    // decode the JSON data
    $result = json_decode($string);

    // switch and check possible JSON errors
    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            $error = ''; // JSON is valid // No error has occurred
            break;
        case JSON_ERROR_DEPTH:
            $error = 'The maximum stack depth has been exceeded.';
            break;
        case JSON_ERROR_STATE_MISMATCH:
            $error = 'Invalid or malformed JSON.';
            break;
        case JSON_ERROR_CTRL_CHAR:
            $error = 'Control character error, possibly incorrectly encoded.';
            break;
        case JSON_ERROR_SYNTAX:
            $error = 'Syntax error, malformed JSON.';
            break;
        // PHP >= 5.3.3
        case JSON_ERROR_UTF8:
            $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
            break;
        // PHP >= 5.5.0
        case JSON_ERROR_RECURSION:
            $error = 'One or more recursive references in the value to be encoded.';
            break;
        // PHP >= 5.5.0
        case JSON_ERROR_INF_OR_NAN:
            $error = 'One or more NAN or INF values in the value to be encoded.';
            break;
        case JSON_ERROR_UNSUPPORTED_TYPE:
            $error = 'A value of a type that cannot be encoded was given.';
            break;
        default:
            $error = 'Unknown JSON error occured.';
            break;
    }

    if ($error !== '') {
        return false;
    }

    // everything is OK
    return true;
}


