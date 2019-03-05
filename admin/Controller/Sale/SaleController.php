<?php


namespace Admin\Controller\Sale;


use Admin\Controller\AdminController;
use Admin\Model\Product\Product;


/**
 * Class SaleController
 * @package Admin\Controller\Sale
 */
class SaleController extends AdminController
{
    /**
     * @var Product
     */
    protected $product;



    /**
     * SaleController constructor.
     * @param $di
     * @throws \Exception
     */
    public function __construct($di)
    {
        parent::__construct($di);
        $this->product = $this->load->model('Product');
    }
}