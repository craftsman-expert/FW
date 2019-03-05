<?php


require_once __DIR__ . '/../vendor/autoload.php';

// if (version_compare($ver = PHP_VERSION, $req = FLEXI_PHP_MIN, '<')) {
//     die(sprintf('You are running PHP %s, but Flexi needs at least PHP %s to run.', $ver, $req));
// }


use Engine\Application;
use Engine\DI\DI;
use Engine\Helper\Cookie;
use Engine\Helper\IDGenerator;


session_start();

if (!Cookie::has('APP_SESSION')){
    $app_session_id = session_id();
    Cookie::set('APP_SESSION', $app_session_id, '/',  60*60*24);
}





try{
    // Dependency injection
    $di = new DI();
    $services = require __DIR__ . '/Config/Service.php';

    // Initialization services
    foreach ($services as $service) {
        $provider = new $service($di);
        $provider->init();
    }



    // Init models
    $di->set('model', []);
    $cms = new Application($di);
    $cms->run();

} catch (Exception $e) {
    echo $e->getMessage();
} finally{

}