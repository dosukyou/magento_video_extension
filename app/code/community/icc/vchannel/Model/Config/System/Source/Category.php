<?php
/**
 * @open license
 */

class ICC_Vchannel_Model_Config_System_Source_Category
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        /** $categories ICC_Vchannel_Model_Resource_Category_Collection */
        $categories = Mage::getResourceModel('icc_vchannel/category_collection');
        $categories->addFieldToSelect(array('entity_id', 'title'));
        $categories->prepareTree(true);

        return array_merge(array('0' => 'None'), $this->getCategoryTree($categories));
    }

    /**
     * array|Varien_Data_Collection $categories
     * array $result
     * $level
     * @return array
     */
    public function getCategoryTree($categories, $result = array(), $level = 0)
    {
        if (!empty($categories)) {
            /** $category ICC_Vchannel_Model_Category */
            foreach ($categories as $category) {
                if ($level == $category->getDepth()) {
                    $result[$category->getId()] = str_repeat('- ', $category->getDepth()) . $category->getData('title');
                    $result = $this->getCategoryTree($category->getChildren(), $result, $level + 1);
                }
            }
        }

        return $result;
    }
}
