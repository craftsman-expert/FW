<?php

namespace Admin\Controller;

use Admin\Model\User\User;

use Config;
use Engine\AbstractController;
use Engine\Helper\Common;
use Engine\Helper\Cookie;
use Engine\Helper\Lang;
use Exception;
use General\Model\Language\Language;


/**
 * Class AdminController
 * @property mixed model
 * @package Admin\Controller
 */
class AdminController extends AbstractController
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var User Object
     */
    public $user;


    /**
     * @var Language
     */
    public $language;

    /**
     * @var int идентификатор пользователя в системе
     */
    public $user_id;

    /**
     * @var int идентификатор группы в которой состоит пользователь
     */
    public $user_group_id;
    /**
     * @var array|bool|FALSE
     */
    private $user_info;



    /**
     * AdminController constructor.
     * @param $di
     * @throws Exception
     */
    public function __construct($di)
    {
        parent::__construct($di);
        $this->user = $this->load->model('User');
        $this->language = $this->load->model('Language', 'Language', 'General');

        if (Cookie::has('_uat')){
            if (!$this->user->verification(Cookie::get('_uat'))){
                // не прошел проверку
                header('Location: /admin/login.form');
                exit();
            }
        } else {
            header('Location: /admin/login.form');
            exit();
        }

        $this->user_id = $this->user->getUserId(Cookie::get('_uat'));
        $this->user_info = $this->user->getUserInfo($this->user_id);

        // loading language packages...
        $this->lpm->load('message', $this->lang, ENV);
        $this->lpm->load('catalog', $this->lang, ENV);
        $this->lpm->load('page', $this->lang, ENV);
        $this->lpm->load('sale', $this->lang, ENV);


        // todo: перевод всех конфигурационных файлов в json
        $this->twig->addGlobal('MAIN_MENU', json_decode(file_get_contents(ROOT_DIR . '/admin/Config/menu.json'), true)) ; // Main menu admin-panel
        $this->twig->addGlobal('USER_INFO', $this->user_info) ; // User menu

        $this->twig->addGlobal('HEADER_TITLE',  $this->lpm->translate('page', strtolower(Common::getPath('_')) . '_title'));
        $this->twig->addGlobal('HEADER_SUB_TITLE',  $this->lpm->translate('page', strtolower(Common::getPath('_')) . '_sub_title'));

        // data
        $this->data['language'] = $this->language->getAll();
    }



    /**
     * @return array|bool|FALSE
     */
    public function getUserInfo()
    {
        return $this->user_info;
    }
}