<?php
/**
 * @open license 
 */

class ICC_Vchannel_Block_Item_List
    extends ICC_Vchannel_Block_Abstract
{
    /**  ICC_Vchannel_Model_Resource_Item_Collection */
    protected $_itemCollection;

    /**
     *  ICC_Vchannel_Model_Resource_Item_Collection
     */
    public function getItemCollection()
    {
        if (is_null($this->_itemCollection)) {
            $this->_itemCollection = $this->getCategory()->getItemCollection();
        }
        return $this->_itemCollection;
    }
}
