<?php
/**
 * @open license
 */

class ICC_Vchannel_Block_Item_View
    extends ICC_Vchannel_Block_Abstract
{
    /**
     *  ICC_Vchannel_Model_Item
     */
    public function getItem()
    {
        return Mage::registry('gallery_item');
    }
}
