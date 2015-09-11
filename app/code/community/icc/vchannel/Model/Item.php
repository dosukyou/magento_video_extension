<?php
/**
 * @open license 
 */

class ICC_Vchannel_Model_Item
    extends Mage_Core_Model_Abstract
{
    protected $_eventPrefix    = 'icc_vchannel_item';
    protected $_helper;


    const TYPE_IMAGE = 'image';
    const TYPE_VIDEO = 'video';

    const STATUS_DISABLED = 0;
    const STATUS_ENABLED  = 1;

    /**
     *  ICC_Vchannel_Helper_Item_Interface
     */
    public function getHelper()
    {
        if (is_null($this->_helper)) {
            switch ($this->getData('type')) {
                case self::TYPE_IMAGE:
                    $this->_helper = Mage::helper('icc_vchannel/item_image');
                    break;
                case self::TYPE_VIDEO:
                    $this->_helper = Mage::helper('icc_vchannel/item_video');
                    break;
            }
        }

        if (!$this->_helper instanceof ICC_Vchannel_Helper_Item_Interface) {
            throw new ICC_Vchannel_Exception($this->__('Incorrect helper model'));
        }

        return $this->_helper;
    }

    /**
     * @return array
     */
    public function getAllowedFormats()
    {
        return $this->getHelper()->getAllowedFormats($this);
    }

    protected function _construct()
    {
        $this->_init('icc_vchannel/item');
    }

    /**
     * @return Mage_Core_Model_Abstract|void
     */
    protected function _afterDelete()
    {
        try {
            $this->deleteThumbnail();
        } catch (ICC_Vchannel_Exception $e) {
            Mage::logException($e);
        }

        parent::_afterDelete();
    }

    /**
     * @return $this
     */
    public function deleteThumbnail()
    {
        return $this->_deleteFile('thumbnail');
    }

    /**
     * @return $this
     */
    public function deleteValueFile()
    {
        return $this->_deleteFile('value');
    }

    /**
     * @param string $fieldName
     * @return $this
     * @throws ICC_Vchannel_Exception
     */
    protected function _deleteFile($fieldName)
    {
        $path = Mage::getBaseDir('media') . DS . $this->getData($fieldName);
        if (is_file($path) && is_writeable($path)) {
            unlink($path);
            $this->setData($fieldName, '');
        } else {
            if (file_exists($path)) {
                /** @var $helper ICC_Vchannel_Helper_Data */
                $helper = Mage::helper('icc_vchannel');
                throw new ICC_Vchannel_Exception($helper->__("Can't delete file '%s'", $path));
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return Mage::helper('icc_vchannel')->escapeHtml($this->getData('title'));
    }
}
