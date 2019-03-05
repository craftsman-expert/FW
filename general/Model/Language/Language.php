<?php


namespace General\Model\Language;

use Engine\AbstractModel;
use Engine\Core\Database\ExceptionSafeMySQL;

/**
 * Class Language
 * @package General\Model\Language
 */
class Language extends AbstractModel
{
    /**
     * @return array
     * @throws ExceptionSafeMySQL
     */
    public function getAll(): array
    {
        return $this->db->getAll("SELECT * FROM language");
    }



    /**
     * @param $lang
     *
     * @return int
     * @throws ExceptionSafeMySQL
     */
    public function getId($lang): int
    {
        return (int)$this->db->getOne("SELECT lang.id FROM language lang WHERE lang.postfix = ?s", $lang);
    }
}