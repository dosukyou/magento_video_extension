<?php
/**
 * open license
 */

class ICC_Vchannel_CategoryController
    extends ICC_Vchannel_Controller_Abstract
{
    /**
     */
    public function viewAction()
    {
        /** ICC_Vchannel_Model_Category $category */
        $category = Mage::getModel('icc_vchannel/category');
        $category->load($this->getRequest()->getParam('id'));
        Mage::register('gallery_category', $category);

        $this->loadLayout();
        $this->renderLayout();
    }
}
