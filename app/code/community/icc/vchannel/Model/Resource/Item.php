<?php
/**
 *  open license
 */

class ICC_Vchannel_Model_Resource_Item
    extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('icc_vchannel/item', 'entity_id');
    }

    /**
     * Varien_Object $object
     * string $table
     * @return array
     */
    protected function _prepareDataForTable(Varien_Object $object, $table)
    {
        if (is_array($object->getData('additional'))) {
            $object->setData('additional', Mage::helper('core')->jsonEncode($object->getData('additional')));
        }

        $data = parent::_prepareDataForTable($object, $table);

        return $data;
    }
    /**
     *  Mage_Core_Model_Abstract $object
     *  Mage_Core_Model_Resource_Db_Abstract
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if ($object->getData('additional')) {
            $object->setData('additional', Mage::helper('core')->jsonDecode($object->getData('additional')));
        }

        return parent::_afterLoad($object);
    }
}
