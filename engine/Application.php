<?php

namespace Engine;

use Engine\Core\Exception\ExceptionAdmin;
use Engine\Core\Request\Request;
use Engine\Core\Router\DispatchedRoute;
use Engine\DI\DI;
use Engine\Helper\Common;
use Engine\Helper\Message;
use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use stdClass;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

/**
 * Class Cms
 * @property  logger
 * @package Engine
 */
class Application
{
    /**
     * @var Core\Router\Router
     */
    public $router;
    /**
     * @var Logger
     */
    public $logger;
    /**
     * @var DI
     */
    private $di;



    /**
     * Cms constructor.
     *
     * @param $di
     *
     * @throws Exception
     */
    public function __construct(DI $di)
    {
        $this->di = $di;
        $this->router = $this->di->get('router');
        $this->logger = $this->di->get('logger');
        //$this->logger->pushHandler(new StreamHandler(DOCUMENT_ROOT . '/log/log.log', Logger::WARNING));
    }



    /**
     * Run cms
     */
    public function run()
    {
        try {
            require_once dirname(__DIR__, 1) . '/' . mb_strtolower(ENV) . '/Route.php';

            $this->logger->debug('', [
                'REQUEST_URI' => Common::getPathUrl()
            ]);


            /** @var DispatchedRoute $routerDispatch */
            $routerDispatch = $this->router->dispatch(Common::getMethod(), Common::getPathUrl());

            if ($routerDispatch == null) {
                $routerDispatch = new DispatchedRoute('ErrorController:error');
            }


            list($class, $action) = explode(':', $routerDispatch->getController(), 2);
            $controller = '\\' . ENV . '\\Controller\\' . $class;

            $parameters = $routerDispatch->getParameters();

            if (class_exists($controller)){
                $call_res = call_user_func_array([new $controller($this->di), $action], $parameters);
            } else {
                throw new Exception("Class [$controller] not exists!");
            }


            if ($call_res === false) {
                throw new Exception('Error calling call_user_func_array function!');
            }


        } catch (Twig_Error_Loader $e) {
            $this->logger->critical($e->getMessage(), $e->getTrace());
            echo $e->getMessage();
        } catch (Twig_Error_Runtime $e) {
            $this->logger->critical($e->getMessage(), $e->getTrace());
            echo $e->getMessage();
        } catch (Twig_Error_Syntax $e) {
            echo $e->getMessage();
            $this->logger->critical($e->getMessage(), $e->getTrace());
        } catch (ExceptionAdmin $e) {

            switch (Common::getRouteType(Common::getPathUrl())) {
                case 'PAGE':
                    {
                        echo $e->getMessage();
                        break;
                    }

                case 'DATA':
                    {
                        $obj = new stdClass();
                        $obj->error_code = $e->getCode();
                        $obj->type = $e->getType();
                        $obj->title = $e->getTitle();
                        $obj->msg = $e->getMessage();

                        header("Content-type: application/json; charset=utf-8");
                        echo json_encode($obj, JSON_UNESCAPED_UNICODE);
                    }
                    break;
            }

        } catch (Exception $e) {

            $context = [
                'pathUrl' => Common::getPathUrl(),
                'last_error' => error_get_last()
            ];

            $this->logger->critical($e->getMessage(), $context);
            echo $e->getMessage();
        }
    }
}