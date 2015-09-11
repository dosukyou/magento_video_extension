<?php
/**
 * open license
 */

class ICC_Vchannel_Block_Adminhtml_Category_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    protected $_objectId   = 'id';
    protected $_controller = 'adminhtml_category';
    protected $_blockGroup = 'icc_vchannel';

    /**
     * @return ICC_Vchannel_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('icc_vchannel');
    }

    public function __construct()
    {
        parent::__construct();
        $this->_updateButton('save', 'label', $this->_getHelper()->__('Save Category'));
        $this->_updateButton('delete', 'label', $this->_getHelper()->__('Delete Category'));
    }

    /**
     * Get edit form
     */
    public function getHeaderText()
    {
        /** @var ICC_Vchannel_Model_Category $category */
        $category = Mage::registry('category');
        if ($category->getId()) {
            return $this->_getHelper()->__("Edit category '%s'", $this->escapeHtml($category->getData('title')));
        }
        else {
            return $this->_getHelper()->__('New Category');
        }
    }
}
