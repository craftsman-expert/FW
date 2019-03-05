<?php


namespace Cms\Controller\Shop;


use Cms\Controller\ProtectedController;
use Cms\Model\Cart\Cart;
use Cms\Model\Order\Order;
use Engine\Core\Database\ExceptionSafeMySQL;
use Engine\DI\DI;
use Engine\Helper\Message;
use Exception;

/**
 * Class OrderController
 * @package Cms\Controller
 */
class OrderController extends ProtectedController
{
    /**
     * @var Cart
     */
    private $cart;
    /**
     * @var Order
     */
    private $order;



    /**
     * OrderController constructor.
     *
     * @param $di
     *
     * @throws Exception
     */
    public function __construct(DI $di)
    {
        parent::__construct($di);
        $this->cart = $this->load->model('Cart');
        $this->order = $this->load->model('Order');
    }



    /**
     * Create order
     */
    public function create()
    {
        try {
            $cart_rows = $this->cart->get($this->getAppSessionId());
            if (empty($cart_rows)) {
                Message::error($this->lpm->translate('shop', 'order_not_created'), -1);
            }

            $this->db()->beginTransaction();

            $order_id = $this->order->create($this->getCustomerId());
            $amount_total = 0.0;
            foreach ($cart_rows as $cart_row){
                $amount_total = $amount_total + $cart_row['amount'];

                $this->order->addProduct([
                    'order_id' => $order_id,
                    'product_id' => $cart_row['product_id'],
                    'price' => $cart_row['price'],
                    'count' => $cart_row['quantity']
                ]);

                $this->cart->remove($cart_row['id']);
            }

            $this->order->setAmount($order_id, $amount_total);

            $this->db()->endTransaction();

            Message::success($this->lpm->translate('shop', 'order_created'));
        } catch (ExceptionSafeMySQL $e) {
            $this->db()->rollback();
        }
    }

}