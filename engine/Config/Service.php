<?php
/**
 * It is necessary to set priorities correctly when initializing modules.
 */
return [
    Engine\Service\Config\Provider::class,
    Engine\Service\db\Provider::class,
    // Engine\Service\Memcache\Provider::class,
    Engine\Service\Memcached\Provider::class,
    Engine\Service\Setting\Provider::class,
    Engine\Service\Cache\Provider::class,
    Engine\Service\Router\Provider::class,
    Engine\Service\Request\Provider::class,
    Engine\Service\Load\Provider::class,
    Engine\Service\Plugin\Provider::class,
    Engine\Service\Logger\Provider::class,
    Engine\Service\QRCode\Provider::class,
    Engine\Service\LPManager\Provider::class,
    Engine\Service\Http\Provider::class,
    Engine\Service\Twig\Provider::class,
    Engine\Service\NexmoClient\Provider::class,
];
