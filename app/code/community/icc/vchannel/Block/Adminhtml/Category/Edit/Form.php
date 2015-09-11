<?php
/**
 * open license
 */

class ICC_Vchannel_Block_Adminhtml_Category_Edit_Form
    extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array(
                'id'      => 'edit_form',
                'action'  => $this->getUrl('*/*/save'),
                'method'  => 'post',
                'enctype' => 'multipart/form-data',
                )
        );
        $form->setData('use_container', true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
