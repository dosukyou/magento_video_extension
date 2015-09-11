<?php
/**
 * @open license
 */

class ICC_Vchannel_Block_Adminhtml_Item_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    protected $_objectId   = 'id';
    protected $_blockGroup = 'icc_vchannel';
    protected $_controller = 'adminhtml_item';

    /**
     * @return ICC_Vchannel_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('icc_vchannel');
    }

    /**
     * Class Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->_updateButton('save', 'label', $this->_getHelper()->__('Save'));
        $this->_updateButton('delete', 'label', $this->_getHelper()->__('Delete'));
    }

    /**
     * @return Mage_Core_Block_Abstract
     */
    protected function _prepareLayout()
    {
        /* @var ICC_Vchannel_Model_Item $item */
        $item = Mage::registry('item');

        $this->_formScripts = $item->getHelper()->prepareEditFormScripts($item, $this->_formScripts);

        return parent::_prepareLayout();
    }

    /**
     * Get edit form container header text
     *
     * @return string
     */
    public function getHeaderText()
    {
        /** @var ICC_Vchannel_Model_Item $video */
        $video = Mage::registry('item');
        if ($video->getId()) {
            return $this->_getHelper()->__("Edit Item '%s'", $this->escapeHtml($video->getData('title')));
        }
        else {
            return $this->_getHelper()->__('New Item');
        }
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/gallery_item/list', array('id' => $this->getRequest()->getParam('category')));
    }
}
