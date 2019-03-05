<?php


namespace Cms\Model;


use Engine\DI\DI;
use Engine\AbstractModel;
use General\Model\Language\Language;

/**
 * Class CmsModel
 * @package Cms\Model
 */
class CmsModel extends AbstractModel
{
    /**
     * @var Language
     */
    public $language;

    /**
     * CmsModel constructor.
     *
     * @param DI $di
     *
     * @throws \Exception
     */
    public function __construct(DI $di)
    {
        parent::__construct($di);

        $this->language = $this->load->model('Language', 'Language', 'General');
    }
}