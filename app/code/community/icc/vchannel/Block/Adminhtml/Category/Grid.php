<?php
/**
  open license
 */

class ICC_Vchannel_Block_Adminhtml_Category_Grid
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
        $this->setId('icc_vchannel_category_grid');
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
        /** @var ICC_Vchannel_Model_Resource_Category_Collection $collection */
        $collection = Mage::getResourceModel('icc_vchannel/category_collection');
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'        => $this->_getHelper()->__('ID'),
            'width'         => '50px',
            'index'         => 'entity_id',
            'header_export' => 'entity_id',
        ));

        $this->addColumn('title', array(
            'header'        => $this->_getHelper()->__('Title'),
            'index'         => 'title',
            'header_export' => 'title',
        ));

        $this->addColumn('title', array(
            'header'        => $this->_getHelper()->__('Actions'),
            'index'         => 'title',
            'header_export' => 'title',
        ));

        $this->addColumn('actions', array(
            'header'    => Mage::helper('cms')->__('Action'),
            'width'     => 200,
            'sortable'  => false,
            'filter'    => false,
            'renderer'  => 'icc_vchannel/adminhtml_category_grid_renderer_action',
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
        return $this->getUrl('*/*/categoryAjaxGrid', array('_current' => true));
    }

    /**
     * @param Mage_Core_Model_Abstract $item
     * @return string
     */
    public function getRowUrl($item)
    {
        return $this->getUrl('*/gallery_item/list', array('_current' => true, 'id' => $item->getId()));
    }
}
