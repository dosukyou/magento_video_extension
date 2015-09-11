<?php
/**
 * @open license
 */

abstract class ICC_Vchannel_Controller_Adminhtml_Item_Abstract
    extends Mage_Adminhtml_Controller_Action
{
    /**
     * Prepare menu and handles
     */
    protected function _initLayout()
    {
        $this->loadLayout();
        $this->_setActiveMenu('cms/gallery');
        $this->initLayoutMessages(array('adminhtml/session'));
    }

    /**
     * @return ICC_Vchannel_Model_Item
     */
    abstract protected function _getEntityModel();

    /**
     * Create new video
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * Edit video item
     */
    public function editAction()
    {
        /**  ICC_Vchannel_Model_Item $item */
        $item = $this->_getEntityModel();
        if ($id = $this->getRequest()->getParam('id')) {
            $item->load($id);
        }
        Mage::register('item', $item);
        $this->_initLayout();
        $this->renderLayout();
    }

    /**
     * Save video item
     */
    public function saveAction()
    {
        try {
            $request = $this->getRequest();
            if (!$request->isPost()) {
                Mage::throwException($this->__('Wrong request.'));
            }
            $data  = $request->getPost('item');
            /** @var ICC_Vchannel_Model_Item $model */
            $model = $this->_getEntityModel();
            if (isset($data['id'])) {
                $model->load($data['id']);
                unset($data['id']);
            }

            $model->getHelper()->prepareItemSave($model, $this);
            $model->save();

            if ($model->isObjectNew()) {
                $this->_getSession()->addSuccess($this->__('Item created successfully.'));
            } else {
                $this->_getSession()->addSuccess($this->__('Item information updated successfully.'));
            }

            $this->_redirect('*/gallery_item/list', array('id' => $model->getData('category_id')));
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->_redirectReferer();
        } catch (Exception $e) {
            echo $e->getTraceAsString();die();
            $this->_getSession()->addError($e->getMessage());
            Mage::logException($e);
            $this->_redirectReferer();
        }
    }

    /**
     * Delete video item
     */
    public function deleteAction()
    {
        try {
            $request  = $this->getRequest();
            /** @var ICC_Vchannel_Model_Item $item */
            $item = $this->_getEntityModel();
            $item->load($request->getParam('id'));
            $categoryId = $item->getData('category_id');
            $item->delete();
            $this->_getSession()->addSuccess($this->__('Video was deleted successfully.'));
            $this->_redirect('*/gallery_category/list', array('id' => $categoryId));
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->_redirectReferer();
        } catch (Exception $e) {
            $this->_getSession()->addException($e, $this->__('Something went wrong...'));
            $this->_redirectReferer();
        }
    }
}
