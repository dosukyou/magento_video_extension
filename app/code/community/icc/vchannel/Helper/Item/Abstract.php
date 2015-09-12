<?php
/**
 * @open license 
 */

abstract class ICC_Vchannel_Helper_Item_Abstract
    extends ICC_Vchannel_Helper_Data
    implements ICC_Vchannel_Helper_Item_Interface
{
    /**
     * @return int
     */
    public function getDataMaxSize()
    {
        return min($this->getPostMaxSize(), $this->getUploadMaxSize());
    }

    /**
     * @return string
     */
    public function getPostMaxSize()
    {
        return ini_get('post_max_size');
    }

    /**
     * @return string
     */
    public function getUploadMaxSize()
    {
        return ini_get('upload_max_filesize');
    }

    /**
     * ICC_Vchannel_Model_Item $item
     * @return array
     */
    public function prepareEditFormScripts(ICC_Vchannel_Model_Item $item, array $scripts)
    {
        return $scripts;
    }

    /**
     * ICC_Vchannel_Model_Item $item
     */
    public function prepareItemSave(ICC_Vchannel_Model_Item $item, Mage_Adminhtml_Controller_Action $controller)
    {
        $data = $controller->getRequest()->getPost('item');
        if (isset($data['thumbnail'], $data['thumbnail']['delete']) && !empty($data['thumbnail']['delete'])) {
            $item->deleteThumbnail();
            $item->setData('thumbnail', '');
        } else if (
            isset($_FILES['item']['tmp_name']['thumbnail'])
            && $_FILES['item']['tmp_name']['thumbnail']
        ) {
            try {
                $savedFilePath = $this->_saveFile('item[thumbnail]', array('jpg', 'jpeg', 'png', 'gif'), 'thumbnail');
                $item->setData('thumbnail', $savedFilePath);
            } catch (Mage_Core_Exception $e) {
                throw $e;
            } catch (Exception $e) {
                Mage::logException($e);
                throw new ICC_Vchannel_Exception($this->__("Can't save thumbnail."));
            }
        }

        unset($data['thumbnail'], $data['value']);

        $item->addData($data);

        return $this;
    }

    /**
     
     */
    protected function _saveFile($paramName, $allowedFormats = null, $subDir = null)
    {
        $localPath = 'gallery' . DS . 'data' . DS;
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

    /**
     * ICC_Vchannel_Model_Item $item
     */
    public function prepareAndRenderView(ICC_Vchannel_Model_Item $item, Mage_Core_Controller_Varien_Action $controller)
    {
echo "Rendering";
        $controller->loadLayout(
            array ('default', strtolower($controller->getFullActionName() . '_' . $item->getData('type')))
        );
        $controller->renderLayout();

        return $this;
    }
}
