<?php


namespace Cms\Controller\Account;

use Cms\Model\Customer\Customer;
use Engine\AbstractController;
use Engine\Core\Config\Config;
use Engine\Core\Database\ExceptionSafeMySQL;
use Engine\DI\DI;
use Engine\Helper\Cookie;
use Engine\Helper\Header;
use Engine\Helper\IDGenerator;
use Engine\Helper\Message;
use Engine\Helper\Network;
use Engine\Helper\Obj;
use Engine\Helper\PHPOptions;
use Engine\Helper\Server;

use Engine\Helper\Session;
use Exception;
use General\Model\RequestHistory\RequestHistory;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Nexmo\Client\Exception\Request;



/**
 * Class RegistrationController
 * @package Cms\Controller\Account
 */
class RegistrationController extends AbstractController
{

    /**
     * @var Customer
     */
    protected $customer;
    /**
     * @var RequestHistory
     */
    protected $request_history;



    /**
     * RegistrationController constructor.
     *
     * @param DI $di
     *
     * @throws Exception
     */
    public function __construct(DI $di)
    {
        parent::__construct($di);
        $this->lpm->load('main', $this->lang);
        $this->lpm->load('message', $this->lang);



        $this->customer = $this->load->model('Customer');
        $this->request_history = $this->load->model('RequestHistory', 'RequestHistory', 'General');
    }



    /**
     * Sign up page
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function signUp()
    {
        $this->twig->load('account/sign-up.twig');
        echo $this->twig->render('account/sign-up.twig', $this->data);
    }



    /**
     * Send sms code
     */
    public function sendSMS()
    {
        try {
            // Validation phone number
            $phone = $this->request->mixed('phone', 'NaN');
            $phone = base64_decode($phone);
            $phoneUtil = PhoneNumberUtil::getInstance();
            $swissNumberProto = $phoneUtil->parse($phone, 'CN');

            if (!$phoneUtil->isValidNumber($swissNumberProto)){
                Message::warning($this->lpm->translate('main', 'invalid_phone_number'));
            }

            // print "getCountryCode: " . $swissNumberProto->getCountryCode() . PHP_EOL;
            // print "getNationalNumber: " . $swissNumberProto->getNationalNumber() . PHP_EOL;
            // print "isValidNumber: " . $phoneUtil->isValidNumber($swissNumberProto) . PHP_EOL;
            // print "getRegionCodeForNumber: " . $phoneUtil->getRegionCodeForNumber($swissNumberProto) . PHP_EOL;
            // print "format E164: " . $phoneUtil->format($swissNumberProto, PhoneNumberFormat::E164) . PHP_EOL;

            $phone = $phoneUtil->format($swissNumberProto, PhoneNumberFormat::E164);

            if ($this->customer->hasPhone($phone)){
                $customer_id =  $this->customer->getId(false, $phone);
            } else {
                $customer_id = $this->customer->add([
                    'phone' => $phone
                ]);
            }

            $verification = $this->nexmo_message_client->verify()->start([
                'number' => $phone,
                'brand' => 'Nexmo',
                'code_length' => '4']
            );

            Session::write('nexmo_verification_id', $verification->getRequestId());

            Message::success($this->lpm->translate('main', 'verification_code_sent_by_sms'));

        } catch (Request $e) {
            Message::warning($this->lpm->translate('message', 'unknown_error'), UNKNOWN_ERROR);
        } catch (\Nexmo\Client\Exception\Server $e) {
            Message::warning($this->lpm->translate('message', 'unknown_error'), UNKNOWN_ERROR);
        } catch (\Nexmo\Client\Exception\Exception $e) {
            Message::warning($this->lpm->translate('message', 'unknown_error'), UNKNOWN_ERROR);
        } catch (ExceptionSafeMySQL $e) {
            Message::warning($this->lpm->translate('message', 'unknown_error'), UNKNOWN_ERROR);
        } catch (NumberParseException $e) {
            Message::warning($this->lpm->translate('message', 'invalid_phone_number'), INVALID_PHONE_NUMBER);
        } catch (Exception $e) {
            Message::warning($this->lpm->translate('message', 'unknown_error'), UNKNOWN_ERROR);
        }
    }



    public function checkSMSCode()
    {
        $code = $this->request->mixed('code');

        $request_id = Session::read('nexmo_verification_id', 'xxxxxxxxxxxxxxxx');
        $verification = new \Nexmo\Verify\Verification($request_id);
        try {
             $result = $this->nexmo_message_client->verify()->check($verification, $code);

            Message::success('Авторизация удалась', 0);
        } catch (Request $e) {
            Message::error($this->lpm->translate('main', 'confirmation_code_is_incorrect'), -1);
        } catch (\Nexmo\Client\Exception\Server $e) {
            Message::error($this->lpm->translate('main', 'unknown_error'), UNKNOWN_ERROR);
        } catch (\Nexmo\Client\Exception\Exception $e) {
            Message::error($this->lpm->translate('main', 'unknown_error'), UNKNOWN_ERROR);
        }


        // $access_token = md5($code . IDGenerator::generateID());
        // $customer_id = 0;
        // if ($this->customer->hasPhone()) {
        //
        //     $customer_id = $this->customer->getId($openid);
        //
        //     try {
        //         $this->db()->beginTransaction();
        //
        //         $this->customer->update($customer_id, [
        //             'nickname' => $nickname,
        //             'sex' => $sex,
        //             'language' => $language,
        //             'city' => $city,
        //             'province' => $province,
        //             'country' => $country,
        //             'image' => $headimgurl,
        //             'privilege' => null,
        //         ]);
        //
        //         $this->setSession($customer_id, $access_token, $hash_user_agent, $ip);
        //         $this->db()->endTransaction();
        //
        //     } catch (Exception $e) {
        //         echo $e->getMessage();
        //         $this->db()->rollback();
        //     }
        //
        // } else {
        //
        //     try {
        //         $this->db()->beginTransaction();
        //         $customer_id = $this->customer->add([
        //             'openid' => $openid,
        //             'nickname' => $nickname,
        //             'sex' => $sex,
        //             'language' => $language,
        //             'city' => $city,
        //             'province' => $province,
        //             'country' => $country,
        //             'image' => $headimgurl,
        //             'privilege' => null,
        //         ]);
        //
        //         $this->setSession($customer_id, $access_token, $hash_user_agent, $ip);
        //
        //         $this->db()->endTransaction();
        //     } catch (Exception $e) {
        //         echo $e->getMessage();
        //         $this->db()->rollback();
        //     }
        //
        // }



    }



    /**
     * @throws Exception
     */
    public function WeChatCallback()
    {
        $appid = Config::item('APPID', 'wechat');
        $secret = Config::item('SECRET', 'wechat');

        $hash_user_agent = md5(Header::userAgent());
        $ip = Server::remoteAddress();
        $code = $this->request->mixed('code', false);
        $state = $this->request->mixed('state', false);


        // Getting access_token
        try {
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token";
            $res = $this->http->get($url,

                [
                    'query' => [
                        'appid' => $appid,
                        'secret' => $secret,
                        'code' => $code,
                        'grant_type' => 'authorization_code',
                    ],
                ]
            );


            if (json_validate($res->getBody())) {
                $obj = new Obj(json_decode($res->getBody()));

                $openid = $obj->property('openid', false);

                $url = "https://api.weixin.qq.com/sns/userinfo";
                $res = $this->http->get($url, [
                    'query' => [
                        'access_token' => $obj->property('access_token'),
                        'openid' => $openid,
                    ],
                ]);

                if (json_validate($res->getBody())) {

                    $jsonObj = new Obj(json_decode($res->getBody()));

                    $openid = $jsonObj->property('openid', false);
                    $nickname = $jsonObj->property('nickname', false);
                    $sex = $jsonObj->property('sex', false);
                    $language = $jsonObj->property('language', false);
                    $city = $jsonObj->property('city', false);
                    $province = $jsonObj->property('province', false);
                    $country = $jsonObj->property('country', false);
                    $headimgurl = $jsonObj->property('headimgurl', false);
                    $privilege = $jsonObj->property('privilege', []);


                    $access_token = md5($openid . $nickname . $hash_user_agent . $ip . IDGenerator::generateID());
                    $customer_id = 0;
                    if ($this->customer->hasOpenId($openid)) {

                        $customer_id = $this->customer->getId($openid);

                        try {
                            $this->db()->beginTransaction();

                            $this->customer->update($customer_id, [
                                'nickname' => $nickname,
                                'sex' => $sex,
                                'language' => $language,
                                'city' => $city,
                                'province' => $province,
                                'country' => $country,
                                'image' => $headimgurl,
                                'privilege' => null,
                            ]);

                            $this->setSession($customer_id, $access_token, $hash_user_agent, $ip);
                            $this->db()->endTransaction();

                        } catch (Exception $e) {
                            echo $e->getMessage();
                            $this->db()->rollback();
                        }

                    } else {

                        try {
                            $this->db()->beginTransaction();
                            $customer_id = $this->customer->add([
                                'openid' => $openid,
                                'nickname' => $nickname,
                                'sex' => $sex,
                                'language' => $language,
                                'city' => $city,
                                'province' => $province,
                                'country' => $country,
                                'image' => $headimgurl,
                                'privilege' => null,
                            ]);

                            $this->setSession($customer_id, $access_token, $hash_user_agent, $ip);

                            $this->db()->endTransaction();
                        } catch (Exception $e) {
                            echo $e->getMessage();
                            $this->db()->rollback();
                        }

                    }
                }

            }
        } catch (Exception $e) {
            die($e->getMessage());
        }

        // $location_url = $this->request_history->getLast($this->getAppSessionId(), 'PAGE');
        Network::location('/page/cart.checkout');
    }



    /**
     * @param int    $customer_id
     * @param string $access_token
     * @param string $hash_user_agent
     * @param string $ip
     *
     * @return int
     * @throws \Engine\Core\Database\ExceptionSafeMySQL
     */
    private function setSession(int $customer_id, string $access_token, string $hash_user_agent, string $ip):int
    {
        $session_id = $this->customer->setSession($customer_id, $access_token, $hash_user_agent, $ip);

        Cookie::set('_access_token', $access_token);
        Cookie::set('_session_id', $session_id);
        Cookie::set('_verified', 1);

        return $session_id;
    }
}