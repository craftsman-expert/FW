<?php


namespace Cms\Controller\Home;

use Cms\Controller\PublicController;

/**
 * Class HomeController
 * @package Cms\Controller\Home
 */
class HomeController extends PublicController
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function home()
    {
        $this->twig->load('home/home.twig');
        echo $this->twig->render('home/home.twig', $this->data);
    }



}