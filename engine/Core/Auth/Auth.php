<?php


namespace Engine\Core\Auth;

use Engine\Helper\Cookie;

/**
 * Class Auth
 * @package Engine\Core\Auth
 */
class Auth implements AuthInterface
{
    /**
     * @var bool
     */
    protected $authorized = false;
    protected $hashUser;



    /**
     * @return bool
     */
    public function authorized()
    {
        return $this->authorized;

    }



    /**
     * @return mixed
     */
    public function uat()
    {
        return Cookie::get('_uat', null);
    }



    /**
     * User authorization
     * @param $user
     */
    public function authorize($user)
    {
        Cookie::set('_uat', $user, '/');
    }



    /**
     *
     */
    public function unAuthorize()
    {
        Cookie::delete('_uat');
    }



    /**
     * Generate a new random password salt
     * @return string
     */
    public static function salt()
    {
        return (string)rand(10000000, 99999999);
    }



    /**
     * @param $password
     * @param string $salt
     * @return string
     */
    public static function encryptPassword($password, $salt = '')
    {
        return hash('sha256', $password . $salt);
    }
}