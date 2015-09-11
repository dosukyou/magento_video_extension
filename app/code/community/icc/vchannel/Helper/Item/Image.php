<?php
/**
 * @open license
 */

class ICC_Vchannel_Helper_Item_Image
    extends ICC_Vchannel_Helper_Item_Abstract
{
    protected $_allowedFormats = array('jpeg', 'jpg', 'gif', 'png');

    /**
     *  ICC_Vchannel_Model_Item $item
     */
    public function prepareEditForm(ICC_Vchannel_Model_Item $item, Varien_Data_Form $form)
    {
        $isReadonlyMode = false;
        foreach ($form->getElements() as $element) {
            /** @var Varien_Data_Form_Element_Abstract $element */
            if ($element instanceof Varien_Data_Form_Element_Fieldset
                && 'general_information' == $element->getId()) {
                $fieldSet = $element;

                $fieldSet->addField('value', 'image', array(
                    'name'      => 'item[value]',
                    'label'     => $this->__('Image File'),
                    'title'     => $this->__('Image File'),
                    'required'  => !$item->getData('value'),
                    'disabled'  => $isReadonlyMode,
                    'note'         => $this->__('Allowed format(s): <strong>%s</strong>', implode(', ', $item->getAllowedFormats()))
                        . '<br/>'
                        . $this->__('Your server allow you to upload files not more than <strong>%s</strong>. You can modify <strong>post_max_size</strong> (currently is %s) and <strong>upload_max_filesize</strong> (currently is %s) values in php.ini if you want to upload larger files.', $this->getDataMaxSize(), $this->getPostMaxSize(), $this->getUploadMaxSize()),
                ));

                break;
            }
        }

        return $this;
    }

    /**
     * ICC_Vchannel_Model_Item $item
     */
    public function getAllowedFormats(ICC_Vchannel_Model_Item $item = null)
    {
        return $this->_allowedFormats;
    }

    /**
     * ICC_Vchannel_Model_Item $item
     */
    public function prepareItemSave(ICC_Vchannel_Model_Item $item, Mage_Adminhtml_Controller_Action $controller)
    {
        parent::prepareItemSave($item, $controller);

        $data = $controller->getRequest()->getPost('item');
        if (isset($data['value'], $data['value']['delete']) && !empty($data['value']['delete'])) {
            $item->deleteValueFile();
        } else if (
            isset($_FILES['item']['tmp_name']['value'])
            && $_FILES['item']['tmp_name']['value']
        ) {
            try {
                $savedFilePath = $this->_saveFile('item[value]', array('jpg', 'jpeg', 'png', 'gif'), 'image');
                $item->setData('value', $savedFilePath);
            } catch (Mage_Core_Exception $e) {
                throw $e;
            } catch (Exception $e) {
                Mage::logException($e);
                throw new ICC_Vchannel_Exception($this->__("Can't save image."));
            }
        }

        return $this;
    }
}
