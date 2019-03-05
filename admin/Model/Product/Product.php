<?php


namespace Admin\Model\Product;



use Admin\Model\Catalog\Catalog;
use Engine\Core\Database\ExceptionSafeMySQL;
use stdClass;

/**
 * Class Product
 * @package Admin\Model\Catalog\Product
 */
class Product extends Catalog
{


    /**
     * @param array $product
     * @param array $product_description
     *
     * @return int
     * @throws ExceptionSafeMySQL
     */
    public function add(array $product, array $product_description):int
    {
        $this->db()->query("insert into product set ?u", $product);
        $product_description['product_id'] = $this->db()->insertId();
        $this->db()->query("insert into product_description set ?u", $product_description);
        return $product_description['product_id'];
    }



    /**
     * @param int   $id
     * @param array $product
     * @param array $product_description
     *
     * @throws ExceptionSafeMySQL
     */
    public function update(int $id, array $product, array $product_description)
    {
        $this->db()->query("update product set ?u where id = ?i", $product, $id);
        $this->db()->query("insert into product_description set ?u on duplicate key update ?u ", $product_description, $product_description);
    }



    /**
     * @param int $product_id
     * @param int $category
     *
     * @throws ExceptionSafeMySQL
     */
    public function bindToCategory(int $product_id, int $category)
    {
        $this->db()->query("insert ignore into product_to_category set product_id = ?i, category_id = ?i",
            $product_id, $category);
    }



    /**
     * @param int $product_id
     *
     * @throws ExceptionSafeMySQL
     */
    public function unbindToCategory(int $product_id)
    {
        $this->db()->query("delete from product_to_category where product_id = ?i", $product_id);
    }



    /**
     * @param array $data
     *
     * @throws ExceptionSafeMySQL
     */
    public function bindImage(array $data)
    {
        $this->db()->query("insert into product_image set ?u", $data);
    }



    /**
     * @param int $product_id
     *
     * @throws ExceptionSafeMySQL
     */
    public function unbindImages(int $product_id)
    {
        $this->db()->query("delete from product_image where product_id = ?i", $product_id);
    }



    /**
     * @param int $product_id
     *
     * @return array
     * @throws ExceptionSafeMySQL
     */
    public function getImages(int $product_id)
    {
        return $this->db()->getAll("select * from product_image where product_id = ?i", $product_id);
    }



    /**
     * @param int    $offset
     * @param int    $count
     * @param string $fields
     * @param string $lang
     *
     * @return mixed
     * @throws ExceptionSafeMySQL
     */
    public function getRows(int $offset = 0, int $count = 10, $fields = '*', string $lang = 'ru')
    {
        $sql = "select $fields from product p inner join product_description pd on p.id = pd.product_id and pd.language_id = ?i limit ?i, ?i";
        $language_id = $this->language->getId($lang);

        return $this->db()->getAll($sql, $language_id, $offset, $count);
    }



    /**
     * @param int    $id
     * @param string $lang
     *
     * @return stdClass
     * @throws ExceptionSafeMySQL
     */
    public function getRow(int $id, $lang = 'ru')
    {
        $language_id = $this->language->getId($lang);
        $product = $this->db()->getRow("select * from product where id = ?i", $id);
        $product_description = $this->db()->getRow("select * from product_description where product_id = ?i and language_id = ?i", $id, $language_id);

        $obj = new stdClass();
        $obj->product_description = new stdClass();

        foreach ($product as $key => $item) {
            $obj->{$key} = varCast($item);
        }

        if (is_array($product_description)) {
            foreach ($product_description as $key => $item) {
                $obj->product_description->{$key} = varCast($item);
            }
        }

        return $obj;
    }



    /**
     * @param int         $id
     * @param bool|string $lang "ru" or "zh" or "en" ...
     *
     * @throws ExceptionSafeMySQL
     */
    public function delete(int $id, $lang = false):void
    {
        // todo: Implement product removal
    }



    /**
     * @return int
     * @throws ExceptionSafeMySQL
     */
    public function count():int
    {
        return $this->db()->count('product');
    }



    /**
     * @param int $product_id
     *
     * @return array
     * @throws ExceptionSafeMySQL
     */
    public function getLanguages(int $product_id):array
    {
        return $this->db()->getAll("select l.* from product_description pd inner join language l on pd.language_id = l.id where product_id = ?i", $product_id);
    }
    
    
    
    /**
     * @param int    $product_id
     * @param string $lang
     *
     * @return array
     * @throws ExceptionSafeMySQL
     */
    public function getProductCategories(int $product_id, $lang = 'ru')
    {
        return $this->db()->getAll("select cd.category_id id, cd.title from product_to_category ptc inner join category_description cd on ptc.category_id = cd.category_id and ptc.product_id = ?i and cd.language_id = ?i",
            $product_id,
            $this->language->getId($lang)
        );
    }
}