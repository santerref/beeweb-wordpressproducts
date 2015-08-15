<?php

class Beeweb_Wordpressproducts_Model_Api2_Product_Rest_Guest_V1
    extends Mage_Catalog_Model_Api2_Product_Rest_Guest_V1
{
    protected function _prepareProductForResponse(Mage_Catalog_Model_Product $product
    ) {
        parent::_prepareProductForResponse($product);
        $productData = $product->getData();
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
            'wordpress/cart/add', array('id' => $productData['entity_id'])
        );
        $product->addData($productData);

    }
}
