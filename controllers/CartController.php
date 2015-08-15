<?php

class Beeweb_Wordpressproducts_CartController
    extends Mage_Core_Controller_Front_Action
{
    function addAction()
    {
        $productId = $this->getRequest()->getParam('id');
        $cartHelper = Mage::helper('checkout/cart');
        $product = Mage::getModel('catalog/product')->load($productId);
        $addToCartUrl = $cartHelper->getAddUrl($product);
        $this->_redirectUrl($addToCartUrl);
    }
}
