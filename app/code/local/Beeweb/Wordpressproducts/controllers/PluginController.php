<?php

class Beeweb_Wordpressproducts_PluginController
    extends Mage_Core_Controller_Front_Action
{
    const PLUGIN_VERSION = '1.0.3';

    public function verifyAction()
    {
        $code = $this->getRequest()->getParam('code');
        $jsonData = json_encode(array('code' => $code));

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody($jsonData);
    }

    public function storesAction()
    {
        $stores = Mage::app()->getStores(false, true);
        $jsonData = array();
        foreach ($stores as $code => $store) {
            if ($stores[$code]->getIsActive()) {
                $jsonData['stores'][] = $code;
            }
        }
        $defaultStore = Mage::app()->getDefaultStoreView();
        $jsonData['default_store'] = $defaultStore->getCode();
        $jsonData = json_encode($jsonData);

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody($jsonData);
    }

    public function versionAction()
    {
        $jsonData = json_encode(
            array(
                'version' => self::PLUGIN_VERSION
            )
        );

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody($jsonData);
    }
}
