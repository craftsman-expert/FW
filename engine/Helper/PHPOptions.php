<?php


namespace Engine\Helper;
use Exception;

/**
 * Class PHPOptions
 * @package Engine\Helper
 */
class PHPOptions
{

    /**
     * Возвращает путь к директории временных файлов
     * @param bool $new_temp
     * @return string
     * @throws Exception
     */
    public static function getTempDir($new_temp = false)
    {
        if ($new_temp) {
            $temp_dir = sys_get_temp_dir() . $new_temp;
            if (!is_dir($temp_dir)) {
                if (!mkdir($temp_dir, 0777, true)) {
                    throw new Exception('Не удалось создать директорию...');
                }

                return $temp_dir;
            }
            return $temp_dir;
        }
        return sys_get_temp_dir();
    }
}