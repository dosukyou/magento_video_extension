<?php
/**
 * @open license
 */

class ICC_Vchannel_Model_Resource_Category_Collection
    extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Initialize model
     */
    protected function _construct()
    {
        $this->_init('icc_vchannel/category');
    }

    /**
     * @param bool $withDepth
     * @return $this
     */
    public function prepareTree($withDepth = false)
    {
        $this->addFieldToSelect(array('parent_id'));
        foreach ($this->getItems() as $category) {
            /**  $category ICC_Vchannel_Model_Category */
            $parentId = $category->getData('parent_id');
            if ($parentId) {
                /**  $parentCategory ICC_Vchannel_Model_Category */
                $parentCategory = $this->getItemById($parentId);
                if ($parentCategory) {
                    $parentCategory->assignChild($category);
                }
            }
        }

        if ($withDepth) {
            $this->_calculateDepth($this->getItems());
        }

        return $this;
    }

    /**
     */
    protected function _calculateDepth($categories, $depth = 0)
    {
        /** @var $category ICC_Vchannel_Model_Category */
        foreach ($categories as $category) {
            if (0 == $depth && $category->getData('parent_id') > 0) {
                continue;
            }
            $category->setDepth($depth);
            $this->_calculateDepth($category->getChildren(), $depth + 1);
        }

        return $this;
    }
}
