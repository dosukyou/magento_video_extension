<?php
/**
 * @copyright Copyright (c) 2014 Sergey Cherepanov (sergey@cherepanov.org.ua)
 * @license Creative Commons Attribution 3.0 License
 */

class ICC_Vchannel_Block_Adminhtml_Item_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * @return ICC_Vchannel_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('icc_vchannel');
    }

    /**
     * Init Grid default properties
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('icc_vchannel_item_grid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        /** @var $collection ICC_Vchannel_Model_Resource_Item_Collection */
        $collection = Mage::getResourceModel('icc_vchannel/item_collection');
        $collection->addFieldToFilter('category_id', Mage::registry('category')->getId());
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'        => $this->_getHelper()->__('ID'),
            'width'         => '50px',
            'index'         => 'entity_id',
        ));

        $this->addColumn('title', array(
            'header'        => $this->_getHelper()->__('Title'),
            'index'         => 'title',
        ));


        $this->addColumn('status', array(
            'header'        => $this->_getHelper()->__('Status'),
            'index'         => 'status',
            'type'          => 'options',
            'options'       => array(
                ICC_Vchannel_Model_Item::STATUS_ENABLED => $this->__('Enabled'),
                ICC_Vchannel_Model_Item::STATUS_DISABLED => $this->__('Disabled'),
            )
        ));

        $this->addColumn('featured', array(
            'header'        => $this->_getHelper()->__('Featured'),
            'index'         => 'featured',
            'type'          => 'options',
            'options'       => Mage::getModel('adminhtml/system_config_source_yesno')->toArray(),
        ));

        $this->addColumn('type', array(
            'header'        => $this->_getHelper()->__('Type'),
            'index'         => 'type',
            'type'          => 'options',
            'options'       => array(
                ICC_Vchannel_Model_Item::TYPE_IMAGE => $this->__('Image'),
                ICC_Vchannel_Model_Item::TYPE_VIDEO => $this->__('Video'),
            )
        ));

        return parent::_prepareColumns();
    }

    /**
     * Grid url getter
     *
     * @return string current grid url for ajax
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/itemAjaxGrid', array('_current' => true));
    }

    /**
     * @param Mage_Core_Model_Abstract $item
     * @return string
     */
    public function getRowUrl($item)
    {
        switch ($item->getData('type')) {
            default:
                $url = false;
                break;
            case ICC_Vchannel_Model_Item::TYPE_VIDEO:
                $url = $this->getUrl('*/gallery_item_video/edit', array('_current' => true, 'id' => $item->getId(), 'category' => $this->getRequest()->getParam('id')));
                break;
            case ICC_Vchannel_Model_Item::TYPE_IMAGE:
                $url = $this->getUrl('*/gallery_item_image/edit', array('_current' => true, 'id' => $item->getId(), 'category' => $this->getRequest()->getParam('id')));
                break;
        }

        return $url;
    }
}
