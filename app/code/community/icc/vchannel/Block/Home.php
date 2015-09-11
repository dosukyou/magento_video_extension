<?php
/**
 open license 
*/

class ICC_Vchannel_Block_Home
    extends ICC_Vchannel_Block_Category_List
{
    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();

        if (!$this->_template) {
            $this->_template = 'icc/vchannel/home.phtml';
        }

        return $this;
    }
}
