<?php
/**
 * @open license
 */

class ICC_Vchannel_Model_Config_System_Source_Type
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
       return Mage::helper('icc_vchannel/item_video')->getAvailableTypes();
    }
}
