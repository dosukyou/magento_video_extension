<?php
/**
 * open license
 */

class ICC_Vchannel_Adminhtml_Gallery_CategoryController
        extends Mage_Adminhtml_Controller_Action
{
    protected $_entityModel = 'icc_vchannel/category';

    /**
     */
    protected function _getEntityModel()
    {
        return Mage::getModel($this->_entityModel);
    }

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
     * Go to list
     */
    public function indexAction()
    {
        $this->_redirect('*/*/list');
    }

    /**
     * Video categories grid
     */
    public function listAction()
    {
        $this->_initLayout();
        $this->renderLayout();
    }

    /**
     * Render grid for ajax request
     */
    public function categoryAjaxGridAction()
    {
        $this->loadLayout('adminhtml_icc_vchannel_category_grid');
        $this->renderLayout();
    }

    /**
     * New category
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * Edit category
     */
    public function editAction()
    {
        $category = $this->_getEntityModel();
        if ($id = $this->getRequest()->getParam('id')) {
            $category->load($id);
        }
        Mage::register('category', $category);

        $this->_initLayout();
        $this->renderLayout();
    }

    /**
     * Save category
     */
    public function saveAction()
    {
        try {
            $request = $this->getRequest();
            if (!$request->isPost()) {
                Mage::throwException($this->__('Wrong request.'));
            }
            $data  = $request->getPost('category');
            $model = $this->_getEntityModel();
            if (isset($data['id'])) {
                $model->load($data['id']);
                unset($data['id']);
            }
            if (isset($data['thumbnail'], $data['thumbnail']['delete']) && !empty($data['thumbnail']['delete'])) {
                $model->deleteThumbnail();
                $model->setData('thumbnail', '');
            } else if(
                isset($_FILES['category']['tmp_name']['thumbnail'])
                && $_FILES['category']['tmp_name']['thumbnail']
            ) {
                try {
                    $savedFilePath = $this->_saveFile('category[thumbnail]', array('jpg', 'jpeg', 'png', 'gif'), 'category/thumbnail');
                    $model->setData('thumbnail', $savedFilePath);
                } catch (Mage_Core_Exception $e) {
                    throw $e;
                } catch (Exception $e) {
                    Mage::logException($e);
                    throw new ICC_Vchannel_Exception($this->__("Can't save thumbnail."));
                }
            };
            unset($data['thumbnail']);

            $model->addData($data);
            $model->save();
            if ($model->isObjectNew()) {
                $this->_getSession()->addSuccess($this->__('New category created successfully.'));
            } else {
                $this->_getSession()->addSuccess($this->__('Category information updated successfully.'));
            }
            $this->_redirect('*/*');
        } catch (ICC_Vchannel_Exception $e) {
            foreach ($e->getMessages() as $message) {
                $this->_getSession()->addError($message);
            }
            $this->_redirectReferer();
        } catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->_redirectReferer();
        }
    }

    /**
     * Delete category
     */
    public function deleteAction()
    {
        try {
            $request  = $this->getRequest();
            $category = $this->_getEntityModel();
            $category->setId($request->getParam('id'));
            $category->delete();
            $this->_getSession()->addSuccess($this->__('Category was deleted successfully.'));
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $this->_redirectReferer();
        } catch (Exception $e) {
            $this->_getSession()->addException($e, $this->__('Something went wrong...'));
            $this->_redirectReferer();
        }

        $this->_redirect('*/*');
    }

    /**
     * @param string $paramName
     * @param array|null $allowedFormats
     * @param string|null $subDir
     * @return mixed
     */
    protected function _saveFile($paramName, $allowedFormats = null, $subDir = null)
    {
        $localPath = 'video' . DS . 'gallery' . DS;
        if ($subDir) {
            $localPath .= $subDir . DS;
        }
        $absPath   = Mage::getBaseDir('media') . DS . $localPath;
        if (!is_dir($absPath)) {
            mkdir($absPath, 0755, true);
        }
        $uploader = new Mage_Core_Model_File_Uploader($paramName);
        if (is_array($allowedFormats)) {
            $uploader->setAllowedExtensions($allowedFormats);
        }
        $uploader->setAllowRenameFiles(true);
        $result = $uploader->save($absPath);
        return $localPath . $result['file'];
    }
}
