<?php


namespace Admin\Controller;

use Engine\Helper\Network;

/**
 * Class DashboardController
 * @package Admin\Controller
 */
class DashboardController extends AdminController
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Exception
     */
    public function dashboard()
    {

        $this->twig->load('dashboard.twig');
        echo $this->twig->render('dashboard.twig');
    }


    public function admin()
    {
        Network::location('/admin/dashboard');
    }
}