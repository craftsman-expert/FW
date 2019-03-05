<?php


namespace Engine\Core\LPManager;


use Engine\DI\DI;
use Engine\Helper\HelperDI;
use Exception;

/**
 * Language package manager
 * Class PackageLM
 * @package Engine\Core\PackageLM
 */
class LPManager
{
    /**
     *
     */
    const FILE_MASK_LANGUAGE = 'Language/%s/%s.json';
    /**
     * @var DI
     */
    protected $di;

    /**
     *  Language packages
     * @var array
     */
    private $packages = [];



    /**
     * LPManager constructor.
     *
     * @param DI $di
     */
    public function __construct(DI $di)
    {
        $this->di = $di;
    }



    /**
     * @param string $package
     * @param string $key
     *
     * @return string
     */
    public function translate(string $package, string $key): string
    {
        if ($this->has($package)){
            if (isset($this->get($package)->translated_content->{$key})) {
                return $this->get($package)->translated_content->{$key};
            }
        }

        return 'Language pack key not found!';
    }



    /**
     * @param string $package_name
     * @param string $lang
     * @param string $env
     *
     * @throws Exception
     */
    public function load(string $package_name, string $lang, $env = ENV)
    {
        if (!$this->has($package_name)){
            $file = ROOT_DIR . DS . strtolower($env) . DS . sprintf(self::FILE_MASK_LANGUAGE, $lang, $package_name);
            if (!file_exists($file)) throw new Exception('Language pack file not found!', LANGUAGE_PACKAGE_NOT_FOUND);
            $content = json_decode(file_get_contents($file, true));
            $languageName = $this->toUnderScore($package_name);
            $this->push($languageName, $content);
        }
    }



    /**
     * @param $path
     *
     * @return string
     */
    private function toUnderScore($path)
    {
        $replace = preg_replace('/[^a-zA-Z0-9]/', ' ', $path);
        $convert = mb_strtolower($replace);
        $result = lcfirst(str_replace(' ', '_', $convert));
        return $result;
    }



    /**
     * @param string $package_name
     *
     * @return mixed
     */
    private function get(string $package_name)
    {
        if ($this->has($package_name)){
            return $this->packages[$package_name];
        }
    }



    /**
     * @param string $package_name
     *
     * @return bool
     */
    private function has(string $package_name)
    {
        return isset($this->packages[$package_name]) ? true: false;
    }



    /**
     * @return array
     */
    public function getPackages():array
    {
        return $this->packages;
    }



    /**
     * @param array $packages
     */
    public function setPackages(array $packages):void
    {
        $this->packages = $packages;
    }



    /**
     * @param $key
     * @param $value
     */
    private function push($key, $value):void
    {
        if (!isset($this->packages[$key])){
            $this->packages[$key] = $value;
        }
    }


}