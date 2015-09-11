<?php
/**
 * @open license
 */

class ICC_Vchannel_Block_Adminhtml_Item_Edit_Tab_General
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
     * Prepare edit form
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        /** @var ICC_Vchannel_Helper_Data $helper */
        $helper         = $this->_getHelper();
        /** @var ICC_Vchannel_Model_Item $item */
        $item           = Mage::registry('item');
        $isReadonlyMode = false;
        $form           = new Varien_Data_Form();
        $fieldSet       = $form->addFieldset(
            'general_information', array ('legend' => $helper->__('General Information')
            )
        );

        $fieldSet->addType('file', 'ICC_Vchannel_Model_Form_Element_File');

        if ($item->getId()) {
            $fieldSet->addField('entity_id', 'hidden', array(
                'name' => 'item[id]',
            ));
        } else {
            $item->addData(array(
                'category_id' => $this->getRequest()->getParam('category')
            ));
        }

        $fieldSet->addField('title', 'text', array(
            'name'      => 'item[title]',
            'label'     => $helper->__('Title'),
            'title'     => $helper->__('Title'),
            'required'  => true,
            'disabled'  => $isReadonlyMode
        ));

        $fieldSet->addField('featured', 'select', array(
            'name'      => 'item[featured]',
            'label'     => $helper->__('Is Featured'),
            'title'     => $helper->__('Is Featured'),
            'required'  => true,
            'disabled'  => $isReadonlyMode,
            'options'   => array(
                0  => $helper->__('No'),
                1  => $helper->__('Yes'),
            ),
        ));

        $fieldSet->addField('status', 'select', array(
            'name'      => 'item[status]',
            'label'     => $helper->__('Status'),
            'title'     => $helper->__('Status'),
            'required'  => true,
            'disabled'  => $isReadonlyMode,
            'options'   => array(
                ICC_Vchannel_Model_Item::STATUS_DISABLED => $helper->__('Disabled'),
                ICC_Vchannel_Model_Item::STATUS_ENABLED  => $helper->__('Enabled'),
            ),
        ));

        $fieldSet->addField('category_id', 'select', array(
            'name'      => 'item[category_id]',
            'label'     => $helper->__('Category'),
            'title'     => $helper->__('Category'),
            'required'  => true,
            'disabled'  => $isReadonlyMode,
            'options'   => Mage::getModel('icc_vchannel/config_system_source_category')->toOptionArray(),
        ));

        $fieldSet->addField('thumbnail', 'image', array(
            'name'      => 'item[thumbnail]',
            'label'     => $helper->__('Thumbnail'),
            'title'     => $helper->__('Thumbnail'),
            'required'  => false,
            'disabled'  => $isReadonlyMode,
        ));

        $fieldSet->addField('description', 'textarea', array(
            'name'      => 'item[description]',
            'label'     => $helper->__('Description'),
            'title'     => $helper->__('Description'),
            'required'  => false,
            'disabled'  => $isReadonlyMode,
        ));

        $item->getHelper()->prepareEditForm($item, $form);

        Mage::dispatchEvent('icc_vchannel_item_edit_tab_general_prepare_form', array('form' => $form, 'item' => $item));

        foreach ($item->getData() as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    $elementName = $key . '_' . $k;
                    if ($element = $form->getElement($elementName)) {
                        $element->setValue($v);
                    }
                }
            } else {
                if ($element = $form->getElement($key)) {
                    $element->setValue($value);
                }
            }
        }

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
