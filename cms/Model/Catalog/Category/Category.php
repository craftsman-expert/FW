<?php


namespace Cms\Model\Catalog\Category;

use Cms\Model\Catalog\Catalog;
use Engine\Core\Database\ExceptionSafeMySQL;

/**
 * Class Category
 * @package Cms\Model\Catalog\Category
 */
class Category extends Catalog
{



    /**
     * @param int $id
     * @param string $lang
     * @return array|FALSE
     * @throws ExceptionSafeMySQL
     */
    public function getRow(int $id, $lang = 'ru')
    {
        $fields = "c.*, cd.title, cd.description, cd.meta_title, cd.meta_description, cd.meta_keyword";
        $sql = "select ?p from category c inner join category_description cd on c.id = cd.category_id and c.id = ?i and cd.language_id = ?i";
        $language_id = $this->language->getId($lang);
        return $this->db()->getRow($sql,$fields, $id, $language_id);
    }



    /**
     * @param int $id
     *
     * @return int
     * @throws ExceptionSafeMySQL
     */
    public function getParentId(int $id): int
    {
        $sql = "select parent_id from category where id = ?i";
        return (int)$this->db()->getOne($sql, $id);
    }



    /**
     * @param int    $offset
     * @param int    $count
     * @param string $lang
     *
     * @return array
     * @throws ExceptionSafeMySQL
     */
    public function getAll(int $offset = 0, int $count, string $lang = 'ru')
    {
        $language_id = $this->language->getId($lang);
        $sql = "select c.id, c.parent_id, cd.title from category as c inner join category_description cd on c.id = cd.category_id AND c.id IN (SELECT category_id FROM product_to_category GROUP BY category_id) and cd.language_id = ?i ORDER BY `parent_id` ASC limit ?i, ?i";
        return $this->db()->getAll($sql, $language_id, $offset, $count);
    }



    /**
     * @param int    $offset
     * @param int    $count
     * @param string $lang
     *
     * @return array
     * @throws ExceptionSafeMySQL
     */
    public function getRoot(int $offset = 0, int $count, string $lang = 'ru')
    {
        $language_id = $this->language->getId($lang);
        $sql = "select c.id, c.parent_id, cd.title from category as c inner join category_description cd on c.id = cd.category_id AND c.id IN (SELECT category_id FROM product_to_category GROUP BY category_id) and cd.language_id = ?i and c.parent_id = 0 ORDER BY `parent_id` ASC limit ?i, ?i";
        return $this->db()->getAll($sql, $language_id, $offset, $count);
    }



    /**
     * @param int    $id
     * @param int    $offset
     * @param int    $count
     * @param string $lang
     *
     * @return array
     * @throws ExceptionSafeMySQL
     */
    public function getChildren(int $id, int $offset = 0, int $count, string $lang = 'ru')
    {
        $language_id = $this->language->getId($lang);
        $sql = "SELECT c.*, cd.title from category c INNER JOIN category_description cd ON c.id = cd.category_id  AND cd.language_id = ?i and c.parent_id  IN (SELECT id FROM category WHERE id = ?i) AND c.id IN (SELECT category_id FROM product_to_category GROUP BY category_id) limit ?i, ?i";
        return $this->db()->getAll($sql, $language_id, $id, $offset, $count);

    }





    /**
     * @return int
     * @throws ExceptionSafeMySQL
     */
    public function count(): int
    {
        return  $this->db()->count("category");
    }
}