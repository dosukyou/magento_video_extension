<?php
/**
 * open license
 */

class ICC_Vchannel_Adminhtml_Gallery_Item_VideoController
    extends ICC_Vchannel_Controller_Adminhtml_Item_Abstract
{
    /**
     * @return ICC_Vchannel_Model_Item
     */
    protected function _getEntityModel()
    {

        /** @var Mage_Core_Model_Abstract $item */
        $item = Mage::getModel('icc_vchannel/item');
        $item->setData('type', ICC_Vchannel_Model_Item::TYPE_VIDEO);

        return $item;
    }
}
