<?php
/**
 * open license
 */

class ICC_Vchannel_Adminhtml_Gallery_ItemController
    extends Mage_Adminhtml_Controller_Action
{
    /**
     * Prepare menu and handles
     */
    protected function _initLayout()
    {
        $this->loadLayout();
        $this->_setActiveMenu('cms/gallery');
        $this->initLayoutMessages(array('adminhtml/session'));
    }

    /**
     * Video items grid
     */
    public function listAction()
    {
        try {
            $category = Mage::getModel('icc_vchannel/category')->load($this->getRequest()->getParam('id'));
            if (!$category->getId()) {
                throw new ICC_Vchannel_Exception($this->__('Category not found or no longer exists'));
            }

            Mage::register('category', $category);

            $this->_initLayout();
            $this->renderLayout();
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->_redirect('*/gallery_category');
        }
    }

    /**
     * Video items grid
     */
    public function itemAjaxGridAction()
    {
        $this->loadLayout('adminhtml_gallery_item_list_grid');
        $this->renderLayout();
    }
}
