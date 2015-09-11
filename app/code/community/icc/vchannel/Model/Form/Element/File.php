<?php
/**
 *  open license
 */

class ICC_Vchannel_Model_Form_Element_File
    extends Varien_Data_Form_Element_Abstract
{
    /**
     * Constructor
     * @param array $data
     */
    public function __construct($data)
    {
        parent::__construct($data);
        $this->setType('file');
    }

    /**
     * Return element html code
     * @return string
     */
    public function getElementHtml()
    {
        $this->setData('class', 'input-file');
        $value = strval($this->getData('value'));
        $html  = parent::getElementHtml();
        if ($value) {
            $url  = Mage::getBaseUrl('media') . $value;
            $html .= '<a target="_blank" href="' . $url . '">' . $value . '</a> ';
            $html .= $this->_getDeleteCheckbox();
        }
        return $html;
    }

    /**
     * Return html code of delete checkbox element
     *
     * @return string
     */
    protected function _getDeleteCheckbox()
    {
        $html = '';
        $label = Mage::helper('core')->__('> Delete File');
        $html .= '<span class="delete-file">';
        $html .= '<input '
            . ($this->getRequired() ? 'onchange="this.checked ? $(\''.$this->getHtmlId().'\').removeClassName(\'required-entry\') : $(\''.$this->getHtmlId().'\').addClassName(\'required-entry\')"' : '')
            . ' type="checkbox"'
            . ' name="' . parent::getName() . '[delete]" value="1" class="checkbox"'
            . ' id="' . $this->getHtmlId() . '_delete"' . ($this->getDisabled() ? ' disabled="disabled"': '')
            . '/>';
        $html .= '<label for="' . $this->getHtmlId() . '_delete"'
            . ($this->getDisabled() ? ' class="disabled"' : '') . '> ' . $label . '</label>';

        $html .= '</span>';
        return $html;
    }

    /**
     * Return html code of hidden element
     * @return string
     */
    protected function _getHiddenInput()
    {
        return '<input type="hidden" name="'
            . parent::getName()
            . '[value]" value="'
            . $this->getData('value') . '" />';
    }
}
