<?php


namespace Admin\Controller\Sale\Order;


use Admin\Controller\Sale\SaleController;
use Admin\Model\Order\Order;
use Engine\Core\Database\ExceptionSafeMySQL;
use Engine\Helper\Message;
use Exception;
/**
 * Class OrderController
 * @package Admin\Controller\Sale\Order
 */
class OrderController extends SaleController
{

    /**
     * @var Order
     */
    protected $order;



    /**
     * OrderController constructor.
     * @param $di
     * @throws Exception
     */
    public function __construct($di)
    {
        parent::__construct($di);
        $this->order = $this->load->model('Order','Order');
    }



    /**
     * @throws ExceptionSafeMySQL
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function orderManager()
    {
        $obj = new \stdClass();
        $obj->recordsTotal = $this->order->count();
        $obj->recordsFiltered = $this->order->count();
        $obj->data = $this->order->getRows();

        $this->data['order'] = $obj;

        $this->twig->load('sale/order/order-manager.twig');
        echo $this->twig->render('sale/order/order-manager.twig', $this->data);
    }



    /**
     * @throws ExceptionSafeMySQL
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function orderDetails()
    {
        $id = $this->request->get('id', 0);

        $order = (object)$this->order->getRow($id);
        $order->items = $this->order->getProductRows($id);

        if ($order->openid == null){
            $order->openid = 'Нет';
        }

        $this->data['order'] = $order;
        $this->twig->load('sale/order/details.twig');
        echo $this->twig->render('sale/order/details.twig', $this->data);
    }



    /**
     * @throws ExceptionSafeMySQL
     */
    public function delete()
    {
        $id = $this->request->mixed('id', 0);

        $obj = new \stdClass();
        $obj->data = $this->order->delete($id);

        Message::success($this->lpm->translate('sale', 'order_deleted'));
    }



    /**
     * @throws ExceptionSafeMySQL
     */
    public function getRows()
    {
        $draw = $this->request->mixed('draw', 0);
        $start = $this->request->mixed('start', 0);
        $length = $this->request->mixed('length', 10);
        $search = $this->request->mixed('search', false);
        $columns = $this->request->mixed('columns', []);

        $obj = new \stdClass();
        $obj->recordsTotal = $this->order->count();
        $obj->recordsFiltered = $this->order->count();
        $obj->data = $this->order->getRows($start, $length, 'ORDER BY `create_at` DESC');

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($obj, JSON_UNESCAPED_UNICODE);
    }
}