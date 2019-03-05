<?php


namespace Admin\Controller\Catalog\Product;

use Admin\Controller\Catalog\CatalogController;
use Admin\Model\Product\Product;
use Engine\Core\Config\Config;
use Engine\Core\Database\ExceptionSafeMySQL;
use Engine\Helper\Common;
use Engine\Helper\Helper;
use Engine\Helper\Lang;
use Engine\Helper\Message;
use Engine\Helper\Obj;
use Exception;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use RedBeanPHP\R;
use RedBeanPHP\RedException;


/**
 * Class ProductController
 * @package Admin\Controller\Catalog\Product
 */
class ProductController extends CatalogController
{

    /**
     * @var Product
     */
    protected $product;



    /**
     * ProductController constructor.
     * @param $di
     * @throws Exception
     */
    public function __construct($di)
    {
        parent::__construct($di);

        $this->product = $this->load->model('Product','Product');
    }



    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function list()
    {
        $this->twig->load('catalog/product/list.twig');
        echo $this->twig->render('catalog/product/list.twig', $this->data);
    }



    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function new()
    {

        $this->twig->load('catalog/product/new.twig');
        echo $this->twig->render('catalog/product/new.twig', $this->data);
    }



    /**
     * @throws ExceptionSafeMySQL
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws Exception
     */
    public function edit()
    {
        $id = (int)$this->request->mixed('id', 0);
        $lang = $this->request->mixed('lang', 'ru');

        $this->data['language'] = $this->product->getLanguages($id);
        $this->data['product'] = $this->product->getRow($id, $lang);
        $this->data['product_images'] = $this->product->getImages($id);
        $this->data['product_categories'] = $this->product->getProductCategories($id, $lang);

        if (!is_file(ROOT_DIR . DS . $this->data['product']->image)){
            $this->data['product']->image = Config::item('DEFAULT_IMAGE');
        }

        $this->twig->load('catalog/product/edit.twig');
        echo $this->twig->render('catalog/product/edit.twig', $this->data);
    }



    /**
     * @throws ExceptionSafeMySQL
     */
    public function delete()
    {
        $lang = $this->request->mixed('lang', false);
        $id = $this->request->mixed('id', null);

        $this->product->delete($id, $lang);

        Message::success(Lang::instance()->catalog->product->message->deleted);
        exit();
    }



    public function add()
    {
        $images = [];
        if ($this->request->mixed('images', false)){
            $images = explode(',', $this->request->mixed('images', false));
        }

        try{
            $this->db()->beginTransaction();
            $id = $this->product->add(
                [
                    'article' => $this->request->mixed('article', null),
                    'price' => $this->request->mixed('price', 0),
                    'old_price' => $this->request->mixed('old_price', 0),
                    'image' => $this->request->mixed('image', null),
                    'status' => $this->request->mixed('status', 0),
                ],

                [
                    'language_id' => (int)$this->language->getId($this->request->mixed('lang', 'ru')),
                    'title' => $this->request->mixed('title', null),
                    'description' => $this->request->mixed('description', ''),
                ]);

            $this->product->unbindImages($id);
            foreach ($images as $img){
                $this->product->bindImage([
                    'product_id' => $id,
                    'image' => $img
                ]);
            }

            $this->db()->endTransaction();
            Message::success(Lang::instance()->catalog->product->message->saved);
        }catch(Exception $e){
            $this->db()->rollback();
            Message::error($e->getMessage());
            exit();
        }
    }





    /**
     *
     *
     */
    public function update()
    {
        $obj = new Obj($this->request->jsonObj());
        $obj->property('article', null);

        try{
            $this->db()->beginTransaction();
            $this->product->update(
                $obj->property('id', null),
                [
                    'article' =>        $obj->property('article', null),
                    'price' =>          $obj->property('price', null),
                    'old_price' =>      $obj->property('old_price', null),
                    'image' =>          $obj->property('image', null),
                    'status' =>         $obj->property('status', null),
                    'quantity_stock' => $obj->property('quantity_stock', null)
                ],

                [
                    'product_id' =>     $obj->property('id', null),
                    'language_id' =>    (int)$this->language->getId($obj->property('lang', 'ru')),
                    'title' =>          $obj->property('title', null),
                    'description' =>    $obj->property('description', null)
                ]);

            // bind images
            $this->product->unbindImages($obj->property('id', null));
            foreach ($obj->property('images', []) as $img){
                $this->product->bindImage([
                    'product_id' => $obj->property('id', null),
                    'image' => $img
                ]);
            }

            // bind to category
            $this->product->unbindToCategory($obj->property('id', null));
            foreach ($obj->property('category', []) as $item){
                $this->product->bindToCategory(
                    $obj->property('id', null),
                    $item
                );
            }

            $this->db()->endTransaction();
            Message::success(Lang::instance()->catalog->product->message->saved);
        }catch(Exception $e){
            $this->db()->rollback();
            Message::error($e->getMessage());
            exit();
        }

    }



    /**
     * @throws Exception
     */
    public function getRows()
    {
        $draw = $this->request->mixed('draw', 0);
        $start = $this->request->mixed('start', 0);
        $length = $this->request->mixed('length', 10);
        $search = $this->request->mixed('search', false);
        $columns = $this->request->mixed('columns', []);
        $lang = $this->request->mixed('lang', 'ru');

        $fields = 'p.id, pd.title, p.image, p.price, p.create_at';

        $obj = new \stdClass();
        $obj->recordsTotal = $this->product->count();
        $obj->recordsFiltered = $this->product->count();
        $obj->data = $this->product->getRows($start, $length, $fields, $lang);


        // TODO: In the future, you need to solve the issue of removal in a separate function.
        $imagine = new Imagine();
        foreach ($obj->data as $key => $item){

            if (!isset($item['image'])){
                $item['image'] = Config::item('DEFAULT_IMAGE');
            }

            $dir_img_miniatures = Config::item('DIR_IMG_MINIATURES');

            if (!file_exists(ROOT_DIR . $dir_img_miniatures))
                mkdir(ROOT_DIR . $dir_img_miniatures, 755);

            $file_img_miniature = $dir_img_miniatures . '/' . md5($item['image']) . '.' . extractFileExt($item['image']);

            if (!file_exists(ROOT_DIR . $file_img_miniature)){
                if (is_file(ROOT_DIR . $item['image'])){
                    $image = $imagine->open(ROOT_DIR . $item['image']);
                    $size  = $image->getSize();
                    $new_size = $size->heighten(50);

                    $image->resize(new Box($new_size->getWidth(), $new_size->getHeight()));
                    $image->save(ROOT_DIR . $file_img_miniature);
                }
            }

            $obj->data[$key]['image'] = $file_img_miniature; // minnows
        }


        Helper::echoJsonUtf8($obj);
        exit();
    }
}