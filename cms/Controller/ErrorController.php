<?php


namespace Cms\Controller;

use Engine\AbstractController;


/**
 * Class ErrorController
 * @package Cms\Controller
 */
class ErrorController extends AbstractController
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function error()
    {
        $this->twig->load('/error/error.twig');
        echo $this->twig->render('/error/error.twig', $this->data);
    }
}