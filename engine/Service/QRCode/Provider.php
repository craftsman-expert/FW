<?php
/**
 * Created by PhpStorm.
 * User: igorp
 * Date: 12.09.2018
 * Time: 14:52
 */

namespace Engine\Service\QRCode;


use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Engine\Service\AbstractProvider;

class Provider extends AbstractProvider
{

    /**
     * Наименование сервиса
     * @var string
     */
    public $serviceName = 'QRCode';

    /**
     * @return mixed
     */
    function init ()
    {
        $options = new QROptions([
            'version'      => 7,
            'outputType'   => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'     => QRCode::ECC_L,
            'scale'        => 5,
            'imageBase64'  => false,
            'moduleValues' => [
                // finder
                1536 => [0, 63, 255], // dark (true)
                6    => [255, 255, 255], // light (false), white is the transparency color and is enabled by default
                // alignment
                2560 => [255, 0, 255],
                10   => [255, 255, 255],
                // timing
                3072 => [255, 50, 0],
                12   => [255, 255, 255],
                // format
                3584 => [67, 191, 84],
                14   => [255, 255, 255],
                // version
                4096 => [62, 174, 190],
                16   => [255, 255, 255],
                // data
                1024 => [0, 0, 0],
                4    => [255, 255, 255],
                // darkmodule
                512  => [0, 0, 0],
                // separator
                8    => [255, 255, 255],
                // quietzone
                18   => [255, 255, 255],
            ],
        ]);

        $qrCode = new QRCode($options);
        $this->di->set($this->serviceName, $qrCode);
    }
}