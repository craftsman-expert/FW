<?php


namespace Admin\Controller\Catalog\Category;


use Admin\Controller\Catalog\CatalogController;
use Admin\Model\Catalog\Category\Category;
use Engine\Core\Database\ExceptionSafeMySQL;
use Engine\Core\Exception\ExceptionAdmin;
use Engine\Helper\Helper;
use Engine\Helper\Message;
use Exception;
use Lang;
use stdClass;

/**
 * Class CategoryController
 * @package Admin\Controller\Catalog\Category
 */
class CategoryController extends CatalogController
{

    /**
     * @var Category
     */
    private $category;

    /**
     * CategoryController constructor.
     * @param $di
     * @throws Exception
     */
    public function __construct($di)
    {
        parent::__construct($di);

        $this->category = $this->load->model('Category', 'Catalog/Category');
    }



    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function list()
    {
        $this->twig->load('catalog/category/list.twig');
        echo $this->twig->render('catalog/category/list.twig', $this->data);
    }



    /**
     * @api
     * @throws ExceptionSafeMySQL
     */
    public function lookup()
    {
        $q = $this->request->mixed('q', '');

        $obj = new stdClass();
        $obj->error_code = 0;
        $obj->data = $this->category->lookup($q);

        foreach ($obj->data as $key => $item){
            $obj->data[$key]['id'] = (int)$item['id'];
        }

        Helper::echoJsonUtf8($obj);
        exit();
    }



    /**
     * @throws ExceptionSafeMySQL
     */
    public function getRow()
    {
        $id = $this->request->mixed('id', 0);
        $lang = $this->request->mixed('lang', 0);

        $obj = new stdClass();
        $obj->error_code = 0;
        $obj->data = $this->category->getRow($id, $lang);
        $obj->data['parent'] = $this->category->getRow($obj->data['parent_id'], $lang);
        $obj->data['children'] = $this->category->getChildren($id, $lang);

        Helper::echoJsonUtf8($obj);
        exit();
    }



    /**
     * @api
     * @throws ExceptionSafeMySQL
     */
    public function getAll()
    {
        $draw = $this->request->mixed('draw', 0);
        $start = $this->request->mixed('start', 0);
        $length = $this->request->mixed('length', 10);
        $search = $this->request->mixed('search', false);
        $columns = $this->request->mixed('columns', []);
        $lang = $this->request->mixed('lang', 'ru');

        $obj = new stdClass();
        $obj->recordsTotal = $this->category->count();
        $obj->recordsFiltered = $this->category->count();
        $obj->data = $this->category->getAll($start, $length, $lang);

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($obj, JSON_UNESCAPED_UNICODE);
    }





    /**
     * @api
     */
    public function add()
    {

        $this->db()->beginTransaction();
        try{
            $this->category->add(
                [
                    'parent_id' => $this->request->mixed('parent_id', 0),
                    'target' => $this->request->mixed('target', '_self'),
                    'icon' => $this->request->mixed('icon', null)
                ],
                [
                    'language_id' => $this->language->getId($this->request->mixed('lang', 'ru')),
                    'title' => $this->request->mixed('title', null),
                    'description' => $this->request->mixed('description', null),
                    'meta_title' => $this->request->mixed('meta_title', null),
                    'meta_description' => $this->request->mixed('meta_description', null),
                    'meta_keyword' => $this->request->mixed('meta_keyword', null)
                ]);

            $this->db()->commit();
            Message::success(Lang::instance()->catalog->category->message->added);
            exit();
        }catch(Exception $e){
            $this->db()->rollback();

            Message::warning($e->getMessage());
        }
    }



    /**
     * @api
     */
    public function update()
    {
        $this->db()->beginTransaction();
        try{
            $this->category->update(
                (int)$this->request->mixed('id', 0),
                [
                    'parent_id' => $this->request->mixed('parent_id', 0),
                    'target' => $this->request->mixed('target', '_self'),
                    'icon' => $this->request->mixed('icon', null)
                ],
                [
                    'language_id' => $this->language->getId($this->request->mixed('lang', 'ru')),
                    'title' => $this->request->mixed('title', null),
                    'description' => $this->request->mixed('description', null),
                    'meta_title' => $this->request->mixed('meta_title', null),
                    'meta_description' => $this->request->mixed('meta_description', null),
                    'meta_keyword' => $this->request->mixed('meta_keyword', null)
                ]);

            $this->db()->commit();
            Message::success(Lang::instance()->catalog->category->message->updated);
            exit();
        }catch(Exception $e){
            $this->db()->rollback();

            Message::warning($e->getMessage());
        }
    }



    /**
     * Deleting a category and its descendants
     * @api
     * @throws ExceptionAdmin
     */
    public function delete()
    {
        $lang = $this->request->mixed('lang', false);
        $id = $this->request->mixed('id', null);

        $this->db()->beginTransaction();
        try {
            $children = $this->category->getChildren($id, $lang);
            foreach ($children as $item){
                $this->category->delete($item['id'], $lang);
            }
            $this->category->delete($id, $lang);

            $this->db()->commit();
        }catch(ExceptionSafeMySQL $e){

            $this->db()->rollback();

            switch ($e->getCode()){
                case 1451: {
                    Message::warning(Lang::instance()->catalog->category->message->fk_deleted);
                }
                default: throw new ExceptionAdmin(Lang::instance()->message->unknown_error, '', '', '');
            }
        }

        Message::success(Lang::instance()->catalog->category->message->deleted);
    }


}