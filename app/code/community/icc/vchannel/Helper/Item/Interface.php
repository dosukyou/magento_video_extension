<?php
/**
 *  open license
 */

interface ICC_Vchannel_Helper_Item_Interface
{
    /**
     * ICC_Vchannel_Model_Item $item
     * @return array
     */
    public function getAllowedFormats(ICC_Vchannel_Model_Item $item = null);

    /**
     * @param ICC_Vchannel_Model_Item $item
     * @param Varien_Data_Form $form
     * @return ICC_Vchannel_Helper_Item_Interface
     */
    public function prepareEditForm(ICC_Vchannel_Model_Item $item, Varien_Data_Form $form);

    /**
     * @param ICC_Vchannel_Model_Item $item
     * @param array $scripts
     * @return array
     */
    public function prepareEditFormScripts(ICC_Vchannel_Model_Item $item, array $scripts);

    /**
     * @param ICC_Vchannel_Model_Item $item
     * @param Mage_Adminhtml_Controller_Action $controller
     */
    public function prepareItemSave(ICC_Vchannel_Model_Item $item, Mage_Adminhtml_Controller_Action $controller);

    /**
     * @param ICC_Vchannel_Model_Item $item
     * @param Mage_Core_Controller_Varien_Action $controller
     * @return ICC_Vchannel_Helper_Item_Interface
     */
    public function prepareAndRenderView(ICC_Vchannel_Model_Item $item, Mage_Core_Controller_Varien_Action $controller);
}

