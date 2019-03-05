<?php


namespace Cms\Controller\Catalog\Category;


use Cms\Controller\Catalog\CatalogController;
use Cms\Model\Catalog\Category\Category;
use Engine\DI\DI;
use Engine\Helper\Helper;
use stdClass;

/**
 * Class CategoryController
 * @package Cms\Controller\Catalog\Category
 */
class CategoryController extends CatalogController
{
    /**
     * @var Category
     */
    private $category;



    /**
     * CategoryController constructor.
     *
     * @param $di
     *
     * @throws \Exception
     */
    public function __construct(DI $di)
    {
        parent::__construct($di);

        $this->category = $this->load->model('Category', 'Catalog/Category');
    }



    /**
     * @throws \Engine\Core\Database\ExceptionSafeMySQL
     */
    public function getAll()
    {
        $id = (int)$this->request->mixed('id', 0);
        $offset = (int)$this->request->mixed('offset', 0);
        $count = (int)$this->request->mixed('count', 10);

        $outObj = new stdClass();
        $outObj->error_code = 0;
        $outObj->count = $this->category->count();
        $outObj->items = $this->category->getAll($offset, $count, $this->lang);

        Helper::echoJsonUtf8($outObj);
        exit();
    }



    /**
     * @throws \Engine\Core\Database\ExceptionSafeMySQL
     */
    public function navigate()
    {
        $id = (int)$this->request->mixed('id', 0);
        $offset = (int)$this->request->mixed('offset', 0);
        $count = (int)$this->request->mixed('count', 10);

        $outObj = new stdClass();
        $outObj->error_code = 0;
        $outObj->id = $id;
        $outObj->parent_id = $this->category->getParentId($id);
        $outObj->count = $this->category->count();

        if ($id == 0){
            $outObj->items = $this->category->getRoot($offset, $count, $this->lang);
        } else {
            $outObj->items = $this->category->getChildren($id, $offset, $count, $this->lang);
        }


        Helper::echoJsonUtf8($outObj);
        exit();
    }
}