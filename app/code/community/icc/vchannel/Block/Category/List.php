<?php
/**
 * @open license
 */

class ICC_Vchannel_Block_Category_List
    extends ICC_Vchannel_Block_Abstract
{
    /**  ICC_Vchannel_Model_Resource_Item_Collection */
    protected $_categoryCollection;

    /**
     *   ICC_Vchannel_Model_Resource_Item_Collection
     */
    public function getCategoryCollection()
    {
        if (is_null($this->_categoryCollection)) {
            $this->_categoryCollection = Mage::getResourceModel('icc_vchannel/category_collection');

	//	->addFieldToFilter('parent_id', 1);
           	if( $this->getCategory()->getId() ){ 
		$this->_categoryCollection->addFieldToFilter('parent_id', (int) $this->getCategory()->getId());
		}
        }

        return $this->_categoryCollection;
    }
}
