<?php
/**
 * @open license 
 */

class ICC_Vchannel_Block_Adminhtml_Category_Grid_Container
    extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Initialize class prefixes and labels
     */
    public function __construct()
    {
        $this->_blockGroup = 'icc_vchannel';
        $this->_controller = 'adminhtml_category';
        $this->_headerText = $this->__('Manage Categories');
        $this->_addButtonLabel = $this->__('Add New Category');

        parent::__construct();
    }
}
