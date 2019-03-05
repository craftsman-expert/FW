<?php


namespace Admin\Controller;
use elFinder;
use elFinderConnector;
use Engine\Core\Config\Config;
use Engine\Core\Exception\ExceptionAdmin;
use Engine\Helper\Server;
use Exception;

/**
 * Class FileManagerController
 * @package Admin\Controller
 */
class FileManagerController extends AdminController
{

    /**
     * @var string
     */
    private $path;
    /**
     * @var string
     */
    private $url;



    /**
     * FileManagerController constructor.
     * @param $di
     * @throws Exception
     */
    public function __construct($di)
    {
        parent::__construct($di);

        $this->setPath(ROOT_DIR . Config::getImageDir());
        $this->setUrl(sprintf('//%s%s', Server::getDomain(), Config::getImageDir()));
    }



    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function manager()
    {
        $this->twig->load('file-manager/file-manager.twig');
        echo $this->twig->render('file-manager/file-manager.twig');
    }



    /**
     * @throws ExceptionAdmin
     * @throws Exception
     */
    public function connector()
    {
        if (!is_dir($this->path)) {
            if (!mkdir($this->path, 0777, true)) {
                throw new ExceptionAdmin('', 'Не удалось создать директорию...');
            }
        }

        $opts = [
            'debug' => false,
            'roots' => [

            [
                'driver'        => 'LocalFileSystem',           // driver for accessing file system (REQUIRED)
                'path'          => $this->getPath(),                 // path to files (REQUIRED)
                'URL'           => $this->getUrl(), // URL to files (REQUIRED)
                'trashHash'     => 't1_Lw',                     // elFinder's hash of trash folder
                'winHashFix'    => '/', // to make hash same to Linux one on windows too
                'uploadDeny'    => ['all'],                // All Mimetypes not allowed to upload
                'uploadAllow'   => ['image', 'text/plain'],// Mimetype `image` and `text/plain` allowed to upload
                'uploadOrder'   => ['deny', 'allow'],      // allowed Mimetype `image` and `text/plain` only
                'accessControl' => 'access'                     // disable and hide dot starting files (OPTIONAL)
            ], // Trash volume
            [
                'id'            => '1',
                'driver'        => 'Trash',
                'path'          => $this->getPath(),
                'tmbURL'        => $this->getUrl(),
                'winHashFix'    => '/', // to make hash same to Linux one on windows too
                'uploadDeny'    => ['all'],                // Recomend the same settings as the original volume that uses the trash
                'uploadAllow'   => ['image', 'text/plain'],// Same as above
                'uploadOrder'   => ['deny', 'allow'],      // Same as above
                'accessControl' => 'access',                    // Same as above
            ]]];


        // run elFinder
        $connector = new elFinderConnector(new elFinder($opts));
        $connector->run();
    }



    /**
     * @return string
     */
    public function getPath():string
    {
        return $this->path;
    }



    /**
     * @param string $path
     */
    public function setPath(string $path):void
    {
        $this->path = $path;
    }



    /**
     * @return string
     */
    public function getUrl():string
    {
        return $this->url;
    }



    /**
     * @param string $url
     */
    public function setUrl(string $url):void
    {
        $this->url = $url;
    }

}