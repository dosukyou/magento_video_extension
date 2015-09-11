<?php
/**
 * open license 
 */

class ICC_Vchannel_Adminhtml_Gallery_Item_ImageController
    extends ICC_Vchannel_Controller_Adminhtml_Item_Abstract
{
    /**
     */
    protected function _getEntityModel()
    {

        /** @var Mage_Core_Model_Abstract $item */
        $item = Mage::getModel('icc_vchannel/item');
        $item->setData('type', ICC_Vchannel_Model_Item::TYPE_IMAGE);

        return $item;
    }
}
