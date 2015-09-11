<?php
/**
 *  open license 
 */

class ICC_Vchannel_Model_Resource_Category
    extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('icc_vchannel/category', 'entity_id');
    }
}
