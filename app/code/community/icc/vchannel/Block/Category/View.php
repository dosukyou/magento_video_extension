<?php
/**
 * @open license 
 */

class ICC_Vchannel_Block_Category_View
    extends ICC_Vchannel_Block_Abstract
{
    /** ICC_Vchannel_Model_Category */
    protected $_category;

    /**
     * @return string
     */
    public function getItemListHtml()
    {
        if (!$this->getChild('item_list') instanceof ICC_Vchannel_Block_Item_List) {
            $listBlock = $this->getLayout()->createBlock('icc_vchannel/category_view_item_list', 'gallery_item_list', array('template' => 'icc/vchannel/item/list.phtml'));
            $this->append($listBlock, 'item_list');
        }

        $this->getChild('item_list')->setCategory($this->getCategory());

        return $this->getChildHtml('item_list');
    }

    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();

        if (!$this->_template) {
            $this->_template = 'icc/vchannel/category/view.phtml';
        }

        return $this;
    }
}
