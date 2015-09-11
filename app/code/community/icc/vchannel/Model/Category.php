<?php
/**
 *  open license
 */

class ICC_Vchannel_Model_Category
    extends Mage_Core_Model_Abstract
{
    protected $_depth    = 0;
    protected $_children = array();

    /**
     * @param int $value
     * @return $this
     */
    public function setDepth($value)
    {
        $this->_depth = intval($value);

        return $this;
    }

    /**
     * @return int
     */
    public function getDepth()
    {
        return $this->_depth;
    }

    protected function _construct()
    {
        $this->_init('icc_vchannel/category');
    }

    /**
     * @param array $output
     * @return bool
     */
    public function validate(&$output = array())
    {
        /**  $helper ICC_Vchannel_Helper_Data */
        $helper    = Mage::helper('icc_vchannel');
        $result    = true;
        $validator = new Zend_Validate_NotEmpty();
        if (!$validator->isValid($this->getData('title'))) {
            $output[] = $helper->__("Category title can't be empty.");
            $result   = false;
        }

        return $result;
    }

    /**
     * @return $this
     * @throws ICC_Vchannel_Exception
     */
    protected function _beforeSave()
    {
        $validationOutput = array();
        if (!$this->validate($validationOutput)) {
            /** @var $helper ICC_Vchannel_Helper_Data */
            $helper    = Mage::helper('icc_vchannel');
            $exception = new ICC_Vchannel_Exception($helper->__('Invalid category data.'));
            foreach ($validationOutput as $message) {
                $exception->addMessage($message);
            }
            throw $exception;
        }

        return parent::_beforeSave();
    }

    /**
     * @return $this
     */
    protected function _afterDelete()
    {
        try {
            $this->deleteThumbnail();
        } catch (ICC_Vchannel_Exception $e) {
            Mage::logException($e);
        }

        return parent::_afterDelete();
    }

    /**
     * @param ICC_Vchannel_Model_Category $category
     * @return $this
     */
    public function assignChild(ICC_Vchannel_Model_Category &$category)
    {
        $this->_children[$category->getId()] = $category;

        return $this;
    }

    /**
     * @return array
     */
    public function getChildren()
    {
        return $this->_children;
    }

    /**
     * @return $this
     */
    public function deleteThumbnail()
    {
        return $this->_deleteFile('thumbnail');
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
            if (is_file($path)) {
                /** @var $helper ICC_Vchannel_Helper_Data */
                $helper = Mage::helper('icc_vchannel');
                throw new ICC_Vchannel_Exception($helper->__("Can't delete file '%s'", $path));
            }
        }
        return $this;
    }

    /**
     * @return ICC_Vchannel_Model_Resource_Item_Collection
     */
    public function getItemCollection()
    {
        /**  ICC_Vchannel_Model_Resource_Item_Collection $collection */
        $collection = Mage::getResourceModel('icc_vchannel/item_collection');
        $collection->addFieldToFilter('status', ICC_Vchannel_Model_Item::STATUS_ENABLED);
        $collection->addFieldToFilter('category_id', $this->getId());

        return $collection;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return Mage::helper('icc_vchannel')->escapeHtml($this->getData('title'));
    }
}
