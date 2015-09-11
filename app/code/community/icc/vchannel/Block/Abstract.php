<?php
/**
 * @open license
 */

abstract class ICC_Vchannel_Block_Abstract
    extends Mage_Core_Block_Template
{
    /** @var  ICC_Vchannel_Model_Category|null */
    protected $_category;

    protected function _prepareLayout()
    {
        $handles = $this->getLayout()->getUpdate()->getHandles();

        if (!in_array('icc_vchannel_scripts', $handles)) {
            $this->getLayout()->getUpdate()->addHandle('icc_vchannel_scripts');
        }

        parent::_prepareLayout();
    }

    /**
     *  ICC_Vchannel_Model_Category $category
     */
    public function setCategory(ICC_Vchannel_Model_Category $category)
    {
        $this->_category = $category;
    }

    /**
     *  ICC_Vchannel_Model_Category
     */
    public function getCategory()
    {
        if (!$this->_category) {
            if ($this->getData('category_id')) {
                $this->_category = Mage::getModel('icc_vchannel/category')->load($this->getData('category_id'));
            } else {
                $this->_category = Mage::registry('gallery_category');
            }

            if (!$this->_category instanceof ICC_Vchannel_Model_Category) {
                $this->_category = Mage::getModel('ICC_Vchannel/category');
            }
        }

        return $this->_category;
    }

    /**
     * ICC_Vchannel_Model_Category $category
     */
    public function getCategoryUrl(ICC_Vchannel_Model_Category $category)
    {
        return $this->getUrl('vchannel/category/view', array('id' => $category->getId()));
    }

    /**
     * ICC_Vchannel_Model_Item $item
     */
    public function getItemUrl(ICC_Vchannel_Model_Item $item, $params = array())
    {
        return $this->getUrl('vchannel/item/view', array_merge($params, array('id' => $item->getId())));
    }

    /**
     * Varien_Object $object
     */
    public function getThumbnailUrl(Varien_Object $object, $width = null, $height = null)
    {
        if (ICC_Vchannel_Model_Item::TYPE_IMAGE == $object->getData('type') && !$object->getData('thumbnail')) {
            $field = 'value';
        } else {
            $field = 'thumbnail';
        }

        return Mage::helper('icc_vchannel')->getThumbnailUrl($object, $field, $width, $height);
    }

    /**
     * ICC_Vchannel_Model_Item $object
     */
    public function getItemBoxUrl(ICC_Vchannel_Model_Item $object, $width = null, $height = null)
    {
        switch ($object->getData('type')) {
            case ICC_Vchannel_Model_Item::TYPE_IMAGE:
                return Mage::helper('icc_vchannel')->getBoxImageUrl($object, 'value', $width, $height);
                break;
            default:
                return $this->getItemUrl($object);
                break;
        }
    }
}
