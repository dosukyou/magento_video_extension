<?php
/**
 * open license
 */

class ICC_Vchannel_Controller_ItemController
    extends ICC_Vchannel_Controller_Abstract
{
    /**
     * Prepare View Page
     */
    public function viewAction()
    {
        /**  ICC_Vchannel_Model_Item $item */
        $item = Mage::getModel('icc_vchannel/item');
        $item->load($this->getRequest()->getParam('id'));

        Mage::register('gallery_item', $item);

        if ($item->getId()) {
            $item->getHelper()->prepareAndRenderView($item, $this);
        } else {
            $this->_redirectReferer();
        }
    }

    /**
     */
    public function renderLayout($output = '')
    {
        if ('XMLHttpRequest' == $this->getRequest()->getHeader('X-Requested-With')) {
            $this->getLayout()->removeOutputBlock('root');
            $output = 'gallery_item_view';
        }

        return parent::renderLayout($output);
    }
}
