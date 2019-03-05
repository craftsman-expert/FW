<?php


namespace Engine\Helper;

use Ramsey\Uuid\Uuid;
use RedUNIT\Mysql\Bigint;

/**
 * Class IDGenerator
 * @package Engine\Helper
 */
class IDGenerator
{

    /**
     * Unique value generator
     *
     * @param string $prefix
     * @param bool   $more_entropy
     *
     * @return string
     * @throws \Exception
     */
    static function generateID($prefix = "", $more_entropy = true)
    {
        $counter = 0;
        $count = random_int(1, 50);
        $hash = md5(uniqid(microtime(), $more_entropy) . random_int(PHP_INT_MIN, PHP_INT_MAX));
        do{
            $hash = md5($hash);
            $counter++;
        }while($counter < $count);


        return $prefix . $hash;
    }


}