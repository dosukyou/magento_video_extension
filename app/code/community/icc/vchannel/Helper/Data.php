<?php
/**
 * open license
 */

class ICC_Vchannel_Helper_Data
    extends Mage_Core_Helper_Abstract
{
    protected $_placeHolderUrl;

    const XML_CONFIG_CATEGORY_LIST_IMAGE_WIDTH  = 'icc_vchannel/image/category_list_image_width';
    const XML_CONFIG_CATEGORY_LIST_IMAGE_HEIGHT = 'icc_vchannel/image/category_list_image_height';
    const XML_CONFIG_LIST_IMAGE_WIDTH           = 'icc_vchannel/image/list_image_width';
    const XML_CONFIG_LIST_IMAGE_HEIGHT          = 'icc_vchannel/image/list_image_height';
    const XML_CONFIG_BOX_IMAGE_WIDTH            = 'icc_vchannel/image/box_image_width';
    const XML_CONFIG_BOX_IMAGE_HEIGHT           = 'icc_vchannel/image/box_image_height';

    /**
     * @return int
     */
    public function getCategoryListImageWidth()
    {
        return Mage::getStoreConfig(self::XML_CONFIG_CATEGORY_LIST_IMAGE_WIDTH);
    }

    /**
     * @return int
     */
    public function getCategoryListImageHeight()
    {
        return Mage::getStoreConfig(self::XML_CONFIG_CATEGORY_LIST_IMAGE_HEIGHT);
    }

    /**
     * @return int
     */
    public function getListImageWidth()
    {
        return Mage::getStoreConfig(self::XML_CONFIG_LIST_IMAGE_WIDTH);
    }

    /**
     * @return int
     */
    public function getListImageHeight()
    {
        return Mage::getStoreConfig(self::XML_CONFIG_LIST_IMAGE_HEIGHT);
    }

    /**
     *  Varien_Object $object
     */
    public function getThumbnailUrl(Varien_Object $object, $attributeCode, $width = null, $height = null)
    {

        if (is_null($width) && is_null($height)) {
            if ($object instanceof ICC_Vchannel_Model_Category) {
                $width  = $this->getCategoryListImageWidth();
                $height = $this->getCategoryListImageHeight();
            } else {
                $width  = $this->getListImageWidth();
                $height = $this->getListImageHeight();
            }
        }

        return $this->_getImageUrl($object->getData($attributeCode), $width, $height);
    }

    /**
     *  Varien_Object $object
     */
    public function getBoxImageUrl(Varien_Object $object, $attributeCode, $width = null, $height = null)
    {
        if (is_null($width) && is_null($height)) {
            $width  = Mage::getStoreConfig(self::XML_CONFIG_BOX_IMAGE_WIDTH);
            $height = Mage::getStoreConfig(self::XML_CONFIG_BOX_IMAGE_HEIGHT);
        }

        return $this->_getImageUrl($object->getData($attributeCode), $width, $height);
    }

    /**
     *  Varien_Object $object
     */
    public function getImageUrl(Varien_Object $object, $attributeCode, $width = null, $height = null)
    {
        return $this->_getImageUrl($object->getData($attributeCode), $width, $height);
    }

    /**
     * @return string
     * ICC_Vchannel_Exception
     */
    protected function _getPlaceHolderPath()
    {
        if (is_null($this->_placeHolderUrl)) {
            // replace file with skin or default skin placeholder
            $baseDir = Mage::getDesign()->getSkinBaseDir();
            $skinPlaceholder = "/images/catalog/product/placeholder/image.jpg";
            $file = $skinPlaceholder;
            if (file_exists($baseDir . $file)) {
                return $baseDir . $file;
            } else {
                $baseDir = Mage::getDesign()->getSkinBaseDir(array('_theme' => 'default'));
                if (file_exists($baseDir . $file)) {
                    return $baseDir . $file;
                } else {
                    $baseDir = Mage::getDesign()->getSkinBaseDir(array('_theme' => 'default', '_package' => 'base'));
                    if (file_exists($baseDir . $file)) {
                        return $baseDir . $file;
                    }
                }
            }

            throw new ICC_Vchannel_Exception($this->__('Place holder not found'));
        }

        return $this->_placeHolderUrl;
    }


    /**
     * 
     */
    protected function _getImageUrl($imagePath, $width = null, $height = null)
    {
        $imagePath = trim($imagePath);
        $mediaDir = Mage::getBaseDir('media');
        $cacheDir = implode('/', array('gallery', 'image', 'cache'));
        $path     = $mediaDir . DS . $cacheDir;
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        if (!$imagePath || !is_file($mediaDir . DS . $imagePath)) {
            $imagePath = $this->_getPlaceHolderPath();
        }
        $baseFileName = $imagePath;
        $fileName     = (int) $width . '-' . (int) $height . '-' . basename($baseFileName);
        $filePath     = $path . DS . $fileName;
        if (!is_file($filePath)) {
            $imageAbsPath = $mediaDir . DS . $baseFileName;
            if (!is_file($imageAbsPath)) {
                $imageAbsPath = $this->_getPlaceHolderPath();
            }
            $image = new Varien_Image($imageAbsPath);
            $image->keepAspectRatio(true);
            $image->keepFrame(true);
            $image->constrainOnly(true);
            $image->backgroundColor(array(0,0,0));
            if ($width) {
                $image->resize(intval($width), $height);
            }

            $image->save($path, $fileName);
        }

        return Mage::getBaseUrl('media') . $cacheDir . '/' . $fileName;
    }
}
