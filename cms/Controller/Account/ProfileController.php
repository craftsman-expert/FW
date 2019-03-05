<?php

namespace Cms\Controller\Account;



use Cms\Controller\ProtectedController;
use Cms\Model\Order\Order;
use Engine\DI\DI;

/**
 * Class ProfileController
 * @package Cms\Controller\Account
 */
class ProfileController extends ProtectedController
{
    /**
     * @var Order
     */
    private $order;



    /**
     * ProfileController constructor.
     *
     * @param DI $di
     *
     * @throws \Exception
     */
    public function __construct(DI $di)
    {
        parent::__construct($di);
        $this->data['profile'] = $this->customer->get($this->getCustomerId());
        $this->order = $this->load->model('Order');
    }



    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function profile()
    {
        $this->twig->load('account/profile.twig');
        echo $this->twig->render('account/profile.twig', $this->data);
    }



    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Engine\Core\Database\ExceptionSafeMySQL
     */
    public function orders()
    {

        $this->data['orders'] = $this->order->getRows($this->getCustomerId());
        $this->twig->load('account/user-orders.twig');
        echo $this->twig->render('account/user-orders.twig', $this->data);
    }
}