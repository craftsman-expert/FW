<?php


namespace Engine\Core\Log;


/**
 * Class Log
 * @package Engine\Core\Log
 */
class Log extends Loginterface
{

    private $dirOut = 'Log';

    public function __construct ($config = [])
    {
        if (is_array($config)){
            $this->dirOut = $config['dir_out'] ? $config['dir_out'] : 'admin/Log';
        }
    }

    /**
     * @param string $message
     * @param int $code
     * @param int $line
     */
    public function log($message = "", $code = 0, $line = 0)
    {
        $this->write(sprintf("datetime: %s code: %d message: %s line: %d",date('l jS \of F Y h:i:s A'), $code, $message, $line));
    }

    public function clear()
    {


    }

    /**
     * @param string $str
     */
    private function write($str = "")
    {
        file_put_contents(DOCUMENT_ROOT . '/' . $this->dirOut . '/error.log', PHP_EOL . $str, FILE_APPEND);
    }


}