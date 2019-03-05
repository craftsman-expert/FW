<?php


namespace Admin\Model\Catalog\Category;


use Admin\Model\Catalog\Catalog;
use Engine\Core\Database\ExceptionSafeMySQL;

/**
 * Class Category
 * @package Admin\Model\Catalog\Category
 */
class Category extends Catalog
{
    /**
     * @var array
     */
    private $rubric_tree = [];



    /**
     * @param string $q
     * @return array
     * @throws ExceptionSafeMySQL
     */
    public function lookup(string $q)
    {
        $w = [];

        $w[] = $this->db()->parse(" where `title` like '%?p%'", $q);
        $w[] = $this->db()->parse(" or `description` like '%?p%'", $q);
        $w[] = $this->db()->parse(" or `meta_title` like '%?p%'", $q);
        $w[] = $this->db()->parse(" or `meta_description` like '%?p%'", $q);
        $w[] = $this->db()->parse(" or `meta_keyword` like '%?p%'", $q);

        $where = implode('', $w);

        return $this->db()->getAll("select cd.category_id id, cd.title from category_description cd ?p limit 0, 10", $where);
    }



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
     * @param int $offset
     * @param int $count
     * @param string $lang
     * @return array
     * @throws ExceptionSafeMySQL
     */
    public function getAll(int $offset = 0, int $count, string $lang = 'ru')
    {
        $language_id = $this->language->getId($lang);
        $sql = "select c.*, cd.title as category from category as c inner join category_description cd on c.id = cd.category_id and cd.language_id = ?i ORDER BY `parent_id` ASC limit ?i, ?i";
        return $this->db()->getAll($sql, $language_id, $offset, $count);
    }



    /**
     * @param int $id
     * @param string $lang
     * @return array
     * @throws ExceptionSafeMySQL
     */
    public function getParent(int $id, $lang = 'ru'): array
    {
        $w = array();

        $w[] = $this->db->parse(" cd.language_id = ?i", $this->language->getId($lang));
        $w[] = $this->db->parse(" AND c.id = ?i", $id);
        $where = "WHERE ".implode(' ',$w);

        $rubric = $this->db->getAll(
            "SELECT * FROM category AS c INNER JOIN category_description AS cd ON c.id = cd.category_id ?p", $where);

        if (!empty($rubric)){
            foreach ($rubric as &$item){
                array_push($this->rubric_tree, $item);
                $this->getParent($item['parent_id']);
            }
        }
        return $this->rubric_tree;
    }



    /**
     * @param int $id
     * @param string $lang
     * @return array
     * @throws ExceptionSafeMySQL
     */
    public function getChildren(int $id, $lang = 'ru'): array
    {
        if (!$lang) $lang = 'ru';

        $w = array();

        $w[] = $this->db->parse(" cd.language_id = ?i", $this->language->getId($lang));
        $w[] = $this->db->parse(" AND c.parent_id = ?i", $id);
        $where = "WHERE ".implode(' ',$w);

        $rubric = $this->db->getAll(
            "select c.*, cd.title from category as c inner join category_description as cd on c.id = cd.category_id ?p", $where);

        if (!empty($rubric)){
            foreach ($rubric as &$item){
                array_push($this->rubric_tree, $item);
                $this->getChildren($item['id'], $lang);
            }
        }
        return $this->rubric_tree;
    }



    /**
     * @param array $category
     * @param array $category_description
     * @throws ExceptionSafeMySQL
     */
    public function add(array $category, array $category_description)
    {
        $this->db()->query("insert into category set ?u", $category);
        $id = $this->db()->insertId();
        $category_description['category_id'] = $id;
        $this->db()->query("insert into category_description set ?u", $category_description);
    }



    /**
     * @param int $id
     * @param array $category
     * @param array $category_description
     * @throws ExceptionSafeMySQL
     */
    public function update(int $id, array $category, array $category_description)
    {
        $this->db()->query("update category set ?u where id = ?i", $category, $id);

        $category_description['category_id'] = $id;
        $this->db()->query("insert into category_description set ?u on duplicate key update ?u",
            $category_description,
            $category_description);

    }



    /**
     * @param int $id
     * @param bool|string $lang "ru" or "zh" or "en" ...
     * @throws ExceptionSafeMySQL
     */
    public function delete(int $id, $lang = false): void
    {
        if ($lang == false){
            $this->db()->query("delete from category where id = ?i", $id);
        } else {
            $this->db()->query("delete from category_description where category_id = ?i and language_id = ?i",
                $id,
                $this->language->getId($lang)
            );
        }
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