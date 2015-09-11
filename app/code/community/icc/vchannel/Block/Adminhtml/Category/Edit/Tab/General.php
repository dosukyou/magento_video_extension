<?php
/**
 * open license
 */

class ICC_Vchannel_Block_Adminhtml_Category_Edit_Tab_General
    extends Mage_Adminhtml_Block_Widget_Form
    implements  Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     */
    protected function _getHelper()
    {
        return Mage::helper('icc_vchannel');
    }

    /**
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $helper         = $this->_getHelper();
        /* @var $model ICC_Vchannel_Model_Category */
        $model          = Mage::registry('category');
        $isReadonlyMode = false;
        $form           = new Varien_Data_Form();
        $fieldSet = $form->addFieldset('general_information', array ('legend' => $helper->__('Category Information')));

        $fieldSet->addType('file', 'ICC_Vchannel_Model_Form_Element_File');

        if ($model->getId()) {
            $fieldSet->addField('entity_id', 'hidden', array(
                'name' => 'category[id]',
            ));
        }

        $fieldSet->addField('title', 'text', array(
            'name'      => 'category[title]',
            'label'     => $helper->__('Title'),
            'title'     => $helper->__('Title'),
            'required'  => true,
            'disabled'  => $isReadonlyMode
        ));

        $fieldSet->addField('thumbnail', 'image', array(
            'name'      => 'category[thumbnail]',
            'label'     => $helper->__('Thumbnail'),
            'title'     => $helper->__('Thumbnail'),
            'required'  => false,
            'disabled'  => $isReadonlyMode
        ));

        $fieldSet->addField('description', 'textarea', array(
            'name'      => 'category[description]',
            'label'     => $helper->__('Description'),
            'title'     => $helper->__('Description'),
            'required'  => false,
            'disabled'  => $isReadonlyMode
        ));

        Mage::dispatchEvent('icc_vchannel_category_edit_tab_general_prepare_form', array('form' => $form));

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->_getHelper()->__('General Information');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->_getHelper()->__('General Information');
    }

    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }
}
