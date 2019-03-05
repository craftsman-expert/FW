<?php


namespace Admin\Model;

use Engine\DI\DI;
use Engine\AbstractModel;
use Exception;
use General\Model\Language\Language;
use RedBeanPHP\R;


/**
 * Class AdminModel
 * @package Admin\Model
 */
class AdminAbstractModel extends AbstractModel
{

    /**
     * @var Language
     */
    protected $language;



    /**
     * AdminModel constructor.
     * @param DI $di
     * @throws Exception
     */
    public function __construct(DI $di)
    {
        parent::__construct($di);

        $this->language = $this->load->model('Language', 'Language', 'General');
    }



    /**
     * @param $table
     * @param $field
     * @return string
     */
    protected function getPrimaryKey($table, $field = 'id')
    {
        $book = R::dispense( $table );
    }
}