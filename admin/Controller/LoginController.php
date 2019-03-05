<?php


namespace Admin\Controller;

use Admin\Model\User\User;
use Engine\AbstractController;
use Engine\DI\DI;
use Engine\Helper\Common;
use Engine\Helper\Cookie;
use Engine\Helper\IDGenerator;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

/**
 * Class LoginController
 * @package Admin\Controller
 */
class LoginController extends AbstractController
{

    /**
     * @var User
     */
    private $user;

    public function __construct(DI $di)
    {
        parent::__construct($di);

        $this->user = $this->load->model('User');
    }



    /**
     * @param int $error
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function form($error = 0)
    {
        $data = ['error' => $error];

        $this->twig->load('login/form.twig');
        echo $this->twig->render('login/form.twig', $data);
    }



    /**
     * @throws \Engine\Core\Database\ExceptionSafeMySQL
     */
    public function login()
    {
        $login    = $this->request->mixed('login');
        $password = $this->request->mixed('password');
        $remember = $this->request->mixed('remember');

        $this->auth($login, $password);
    }



    /**
     * @throws \Engine\Core\Database\ExceptionSafeMySQL
     */
    public function logout()
    {
        $user_id = $this->user->getUserId(Cookie::get('_uat'));
        $this->unAuthorize($user_id);

        header('Location: /admin/login.form');
        exit();
    }



    /**
     * @param string $login
     * @param string $password
     *
     * @return string
     * @throws \Engine\Core\Database\ExceptionSafeMySQL
     * @throws \Exception
     */
    private function auth(string $login, string $password)
    {
        $w = array();

        $w[] = $this->db->parse(" up.password = ?s", md5($password));     // и пароль     (md5)
        $w[] = $this->db->parse(" AND u.email = ?s", $login);     // или email
        $w[] = $this->db->parse(" OR u.nickname = ?s", $login); // или логин
        $w[] = $this->db->parse(" OR u.phone = ?s", $login);      // или телефон

        $where = "WHERE ".implode('',$w);

        $user = $this->db->getRow("select u.* from user u inner join user_password up on u.id = up.user_id ?p LIMIT 1",
            $where);

        if (isset($user)){
            $access_token = md5(implode('', $user) . $this->salt());
            $this->setAccessToken((int)$user['id'], $access_token);

            Cookie::set('_uat', $access_token, '/admin');

            header('Location: /admin/dashboard');
            exit();
        } else {
            Common::callController('LoginController:form', ['error' => 1]);
            exit();
        }
    }



    /**
     * @param int $user_id
     *
     * @throws \Engine\Core\Database\ExceptionSafeMySQL
     */
    function unAuthorize(int $user_id): void
    {
        $this->db->query("DELETE FROM user_session WHERE  user_id = ?i", $user_id);

        Cookie::delete('_uat', '/admin');
    }



    /**
     * @param int $user_id
     * @param     $access_token
     *
     * @throws \Engine\Core\Database\ExceptionSafeMySQL
     */
    private function setAccessToken(int $user_id, $access_token)
    {
        $set = [
            'user_id' => $user_id,
            'access_token' => $access_token
        ];
        $this->db->query("INSERT INTO user_session SET ?u ON DUPLICATE KEY UPDATE ?u", $set, $set);
    }



    /**
     * @return string
     * @throws \Exception
     */
    private function salt()
    {
        return IDGenerator::generateID(date('Ymdhmsms'), true);
    }
}