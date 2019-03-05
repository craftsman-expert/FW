<?php


namespace Cms\Controller\Catalog\Product;


use Cms\Controller\Catalog\CatalogController;
use Cms\Model\Catalog\Product\Product;
use Engine\DI\DI;
use Engine\Helper\Common;
use Engine\Helper\Helper;
use stdClass;

/**
 * Class ProductController
 * @package Cms\Controller\Catalog\Product
 */
class ProductController extends CatalogController
{
    /**
     * @var Product
     */
    private $product;



    /**
     * ProductController constructor.
     *
     * @param $di
     *
     * @throws \Exception
     */
    public function __construct(DI $di)
    {
        parent::__construct($di);

        $this->product = $this->load->model('Product', 'Catalog/Product');
    }



    /**
     * @param $product_id
     *
     * @throws \Engine\Core\Database\ExceptionSafeMySQL
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @deprecated
     */
    public function d($product_id)
    {
        $this->data['product']['details'] = $this->product->getRow($product_id);

        $this->twig->load('catalog/product/details.twig');
        echo $this->twig->render('catalog/product/details.twig', $this->data);
    }



    /**
     * @throws \Engine\Core\Database\ExceptionSafeMySQL
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function details()
    {
        $product_id = $this->request->get('product_id', false);

        $this->data['product']['details'] = $this->product->getRow($product_id);

        $this->twig->load('catalog/product/details.twig');
        echo $this->twig->render('catalog/product/details.twig', $this->data);
    }



    /**
     * @throws \Engine\Core\Database\ExceptionSafeMySQL
     */
    public function getRows()
    {
        $offset = $this->request->mixed('offset', 0);
        $count = $this->request->mixed('count', 10);

        $outObj = new stdClass();
        $outObj->error_code = 0;
        $outObj->count = $this->product->count();
        $outObj->items = $this->product->getRows($offset, $count, $this->lang);

        Helper::echoJsonUtf8($outObj);
        exit();
    }



    /**
     * @throws \Engine\Core\Database\ExceptionSafeMySQL
     */
    public function getProducts()
    {
        $category_id = (int)$this->request->mixed('category_id', 0);
        $offset = $this->request->mixed('offset', 0);
        $count = $this->request->mixed('count', 100);

        if ($category_id == 0) {
            $category_id = 116;
        }

        $outObj = new stdClass();
        $outObj->error_code = 0;
        $outObj->count = $this->product->count();
        $outObj->items = $this->product->get($category_id, $offset, $count, $this->lang);

        Helper::echoJsonUtf8($outObj);
        exit();
    }
}