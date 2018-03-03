<?php

class Beeweb_Wordpressproducts_Model_Api2_Product_Rest_Guest_V1
    extends Mage_Catalog_Model_Api2_Product_Rest_Guest_V1
{
    protected function _prepareProductForResponse(Mage_Catalog_Model_Product $product)
    {
        parent::_prepareProductForResponse($product);
        $productData = $product->getData();

        $defaultStoreView = Mage::app()->getDefaultStoreView();
        $storeCode = $this->getRequest()->getParam('___store', $defaultStoreView->getCode());

        Mage::app()->setCurrentStore($storeCode);

        if ($this->getActionType() == self::ACTION_TYPE_ENTITY) {
        } else {
            $productHelper = Mage::helper('catalog/product');
            $productData['url'] = $productHelper->getProductUrl(
                $product->getId()
            );
            $stockItem = $product->getStockItem();
            if (!$stockItem
                || ($stockItem instanceof Varien_Object
                    && $stockItem->getIsInStock() == null)
            ) {
                $stockItem = Mage::getModel('cataloginventory/stock_item');
                $stockItem->loadByProduct($product);
            }
            $productData['is_in_stock'] = $stockItem->getIsInStock();
        }

        $productData['buy_now_url'] = Mage::getUrl(
            'wordpress/cart/add',
            array(
                'id'     => $productData['entity_id'],
                '_store' => $storeCode
            )
        );

        $imageWidth = $this->getRequest()->getParam('image_width');
        $imageHeight = $this->getRequest()->getParam('image_height');
        if (!empty($imageWidth) || !empty($imageHeight)) {
            if (empty($imageWidth)) {
                $imageWidth = $imageHeight;
            } elseif (empty($imageHeight)) {
                $imageHeight = $imageWidth;
            }
        } else {
            $imageWidth = 200;
            $imageHeight = 200;
        }

        $resource = Mage::getSingleton('catalog/product')->getResource();
        $imageFile = $resource->getAttributeRawValue(
            $productData['entity_id'], 'image', Mage::app()->getStore()
        );
        $productData['image_url'] = (string)Mage::helper('catalog/image')
            ->init(
                $product, 'image', $imageFile
            )->resize($imageWidth, $imageHeight)->keepAspectRatio(true)
            ->keepTransparency(true);
        $product->addData($productData);

    }
}
