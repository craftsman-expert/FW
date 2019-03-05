<?php


namespace Cms\Controller\Catalog;


use Cms\Controller\ProtectedController;
use Cms\Controller\PublicController;

/**
 * Class CatalogController
 * @package Cms\Controller\Catalog
 */
class CatalogController extends PublicController
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function catalog()
    {
        $this->twig->load('catalog/catalog.twig');
        echo $this->twig->render('catalog/catalog.twig', $this->data);
    }
}