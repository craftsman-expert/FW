<?php


namespace Cms\Model\Catalog\Product;

use Cms\Model\Catalog\Catalog;
use Engine\Core\Database\ExceptionSafeMySQL;

/**
 * Class Product
 * @package Cms\Model\Catalog\Product
 */
class Product extends Catalog
{


    /**
     * @param int    $offset
     * @param int    $count
     * @param string $lang
     *
     * @return array
     * @throws ExceptionSafeMySQL
     */
    public function getRows(int $offset = 0, int $count = 10, $lang = 'ru')
    {
        $language_id = $this->language->getId($lang);
        $data = $this->db()->getAll("select p.*, pd.title, pd.description from product p inner join product_description pd on p.id = pd.product_id and pd.language_id = ?i  limit ?i, ?i", $language_id, $offset, $count);
        foreach ($data as $key => $item) {
            $data[$key]['images'] = (array) $this->getProductImages($item['id']);
        }
        return $data;
    }



    /**
     * @param        $product_id
     * @param string $lang
     *
     * @return array
     * @throws ExceptionSafeMySQL
     */
    public function getRow($product_id, $lang = 'zh')
    {
        $language_id = $this->language->getId($lang);

        $sql =
            "select p.*, pd.title, pd.description " .
            "from product_to_category ptc " .
            "inner join product p " .
            "inner join product_description pd ".
            "on p.id = pd.product_id and  pd.language_id = ?i and p.id = ?i limit 1";

        $data = $this->db()->getRow($sql, $language_id, $product_id);
        return $data;
    }



    /**
     * @param int    $category_id
     * @param int    $offset
     * @param int    $count
     * @param string $lang
     *
     * @return array
     * @throws ExceptionSafeMySQL
     */
    public function get(int $category_id, int $offset = 0, int $count = 10, $lang = 'ru')
    {
        $language_id = $this->language->getId($lang);

        $sql =  "select p.*, pd.title, pd.description " .
                "from product_to_category ptc " .
                "inner join product p " .
                "inner join product_description pd ".
                "ON p.id = pd.product_id AND p.id = ptc.product_id and pd.language_id = ?i WHERE ptc.category_id = ?i limit ?i, ?i";

        $data = $this->db()->getAll($sql, $language_id, $category_id, $offset, $count);
        foreach ($data as $key => $item) {
            $data[$key]['images'] = (array) $this->getProductImages($item['id']);
        }
        return $data;
    }



    /**
     * @param int $product_id
     *
     * @return array
     * @throws ExceptionSafeMySQL
     */
    public function getProductImages(int $product_id)
    {
        return $this->db()->getCol("select image from product_image where product_id = ?i", $product_id);
    }



    /**
     * @return int
     * @throws ExceptionSafeMySQL
     */
    public function count()
    {
        return (int) $this->db()->getOne("select count(*) from product");
    }
}