<?php
/**
 * @open license
 */

class ICC_Vchannel_Block_Adminhtml_Item_Grid_Container
    extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Initialize class prefixes and labels
     */
    public function __construct()
    {
        $this->_blockGroup = 'icc_vchannel';
        $this->_controller = 'adminhtml_item';
        $this->_headerText = $this->__('Manage Items');

        parent::__construct();

        $this->removeButton('add');
        $this->_addBackButton();

        $this->_addButton('add_image', array(
            'label'     => $this->__('Add New Image'),
            'onclick'   => 'setLocation(\'' . $this->getUrl('*/gallery_item_image/new', array('category' => $this->getRequest()->getParam('id'))) .'\')',
            'class'     => 'add',
        ));

        $this->_addButton('add_video', array(
            'label'     => $this->__('Add New Video'),
            'onclick'   => 'setLocation(\'' . $this->getUrl('*/gallery_item_video/new', array('category' => $this->getRequest()->getParam('id'))) .'\')',
            'class'     => 'add',
        ));
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/gallery_category/list');
    }
}
