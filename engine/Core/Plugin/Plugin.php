<?php
namespace Engine\Core\Plugin;
use Engine\Service;
use Exception;

/**
 * Class Plugin
 * @package Engine\Core\Plugin
 */
class Plugin extends Service
{
    /**
     * @param $directory
     * @throws Exception
     */
    public function install($directory)
    {
        $this->getLoad()->model('Plugin');
        /** @var WoERepository $pluginModel */
        $pluginModel = $this->getModel('plugin');
        if (!$pluginModel->isInstallPlugin($directory)) {
            $pluginModel->addPlugin($directory);
        }
    }
    public function activate($id, $active)
    {
        $this->getLoad()->model('Plugin');
        /** @var WoERepository $pluginModel */
        $pluginModel = $this->getModel('plugin');
        $pluginModel->activatePlugin($id, $active);
    }

    /**
     * @return object
     * @throws Exception
     */
    public function getActivePlugins()
    {
        $this->getLoad()->model('Plugin');
        $pluginModel = $this->getModel('plugin');
        return $pluginModel->getActivePlugins();
    }
}