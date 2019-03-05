<?php

namespace Engine;

use Engine\Core\Setting\Setting;
use Engine\DI\DI;
use Engine\Helper\Cookie;
use Exception;
use stdClass;
use Twig_Environment;

/**
 * Class Load
 * @property  AbstractModel
 * @package Engine
 */
class Load
{
    const MASK_MODEL_ENTITY = '\%s\Model\%s\%s';
    const MASK_MODEL_REPOSITORY = '\%s\Model\%s\%s';
    const MASK_MODEL_API_REPOSITORY = '\%s\%s\Model\%s\%s';
    const FILE_MASK_LANGUAGE_ADMIN = 'Language/%s/%s.json';
    const FILE_MASK_LANGUAGE_CMS = 'Language/%s/%s.json';
    const FILE_MASK_LANGUAGE_GENERAL = 'Language/%s/%s.json';
    /**
     * @var \Engine\DI\DI
     */
    public $di;

    /**
     * @var Twig_Environment
     */
    protected $twig;
    /**
     * @var Setting
     */
    private $setting;



    /**
     * Load constructor.
     * @param DI $di
     */
    public function __construct(DI $di)
    {
        $this->di = $di;
        $this->setting = $this->di->get('setting');
        return $this;
    }


  /**
   * @param       $modelName
   * @param bool  $modelDir
   * @param bool  $env
   * @param array $arg
   *
   * @return bool
   * @throws Exception
   */
    public function model($modelName, $modelDir = false, $env = false, ...$arg)
    {
        $modelName = ucfirst($modelName);
        $modelDir = $modelDir ? str_replace('/', '\\', $modelDir) : $modelName;
        $env = $env ? $env : ENV;

        if ($env == 'Api'){
            $namespaceModel = sprintf(
                self::MASK_MODEL_API_REPOSITORY,
                $env,                            // Environment
                $GLOBALS['API_VERSION'],         // Api version
                $modelDir,                       // Model dir
                $modelName);                     // Model name
        } else {
            $namespaceModel = sprintf(
                self::MASK_MODEL_REPOSITORY,
                $env,
                $modelDir,
                $modelName
            );
        }


        $isClassModel = class_exists($namespaceModel);
        if ($isClassModel) {
            // Set to DI
            $modelRegistry = $this->di->get('model') ? : new stdClass();
            if (!empty($arg)){
                $modelRegistry->{lcfirst($modelName)} = new $namespaceModel($this->di, ...$arg);
            } else {
                $modelRegistry->{lcfirst($modelName)} = new $namespaceModel($this->di);
            }

            $this->di->push('model', $modelRegistry);
            $model = lcfirst($modelName);
            return $this->di->get('model')->$model;
        } else {
            throw new Exception(sprintf('Error loading the model "%s"!', $modelName));
        }
    }



    /**
     * Load language packet <hr>
     *
     * @param string $path Format: [a-z0-9/_]
     * @param string $lang
     * @param string $env
     *
     * @return array|bool
     * @throws Exception
     */
    public function language($path, $lang = 'zh', $env = ENV)
    {
        switch ($env) {
            case 'Admin':
                {
                    return $this->langAdmin($path);
                }
            case 'Cms':
                {
                    return $this->langCms($path, $lang);
                }

            case 'General':
                {
                    return $this->langGeneral($path, $lang, $env);
                }

        }
    }


    /**
     * @param        $path
     * @param string $lang
     *
     * @return array|bool
     * @throws Exception
     */
    private function langCms($path, $lang = 'zh')
    {
        $file = ROOT_DIR . DS . strtolower(ENV) . DS . sprintf(self::FILE_MASK_LANGUAGE_CMS,
                $lang,
                $path
            );

        $content = json_decode(file_get_contents($file, true));

        $languageName = $this->toUnderScore($path);
        $language = $this->di->get('language') ? : new stdClass();
        $language->{$languageName} = $content;
        $this->di->push('language', $language);

        // TODO: Загрузка языковых пакетов и в шаблонизатор twig
        $this->twig = $this->di->get('twig');
        $this->twig->addGlobal('LANGUAGE', $language);
        return $content;
    }



    /**
     * @param        $path
     * @param string $lang
     * @param string $env
     *
     * @return mixed
     */
    private function langGeneral($path, $lang = 'zh', $env = ENV)
    {
        $file = ROOT_DIR . DS . strtolower($env) . DS . sprintf(self::FILE_MASK_LANGUAGE_GENERAL,
                $lang,
                $path
            );

        $content = json_decode(file_get_contents($file, true));

        $languageName = $this->toUnderScore($path);
        $language = $this->di->get('language') ? : new stdClass();
        $language->{$languageName} = $content;
        $this->di->push('language', $language);

        return $content;
    }



    /**
     * @param $path
     * @return array|bool
     */
    private function langAdmin($path)
    {

        $lang = Cookie::get('lang', 'ru');

        $file = sprintf(self::FILE_MASK_LANGUAGE_ADMIN, $lang, $path);
        $content = json_decode(file_get_contents($file, true));

        $languageName = $this->toUnderScore($path);
        $language = $this->di->get('language') ? : new stdClass();
        $language->{$languageName} = $content;
        $this->di->push('language', $language);

        // TODO: Загрузка языковых пакетов и в шаблонизатор twig
        $this->twig = $this->di->get('twig');
        $this->twig->addGlobal('LANGUAGE', $language);
        return $content;
    }



    /**
     * @param string $path
     * @return string
     */
    private function toCamelCase($path)
    {
        $replace = preg_replace('/[^a-zA-Z0-9]/', ' ', $path);
        $convert = mb_convert_case($replace, MB_CASE_TITLE);
        $result = lcfirst(str_replace(' ', '', $convert));
        return $result;
    }



    /**
     * @param $path
     * @return string
     */
    private function toUnderScore($path)
    {
        $replace = preg_replace('/[^a-zA-Z0-9]/', ' ', $path);
        $convert = mb_strtolower($replace);
        $result = lcfirst(str_replace(' ', '_', $convert));
        return $result;
    }


}