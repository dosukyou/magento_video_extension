<?php
/**
 * @open license
 */

class ICC_Vchannel_Helper_Item_Video
    extends ICC_Vchannel_Helper_Item_Abstract
{
    const VIDEO_TYPE_YOUTUBE  = 'youtube';
    const VIDEO_TYPE_FILE     = 'file';
    const VIDEO_TYPE_EMBEDDED = 'embedded';

    protected $_allowedFormats = array('flv', 'mp4', 'mov', 'f4v', '3gp', '3g2');

    protected $_videoWidth  = 624;
    protected $_videoHeight = 450;

    /**
     * ICC_Vchannel_Model_Item $item
     * @return array
     */
    public function getAllowedFormats(ICC_Vchannel_Model_Item $item = null)
    {
        return $this->_allowedFormats;
    }

    /**
     * ICC_Vchannel_Model_Item $item
     * @return array
     */
    public function getAvailableTypes(ICC_Vchannel_Model_Item $item = null)
    {
        return array (
            self::VIDEO_TYPE_YOUTUBE => $this->__('YouTube Video'),
            self::VIDEO_TYPE_FILE    => $this->__('Uploaded File'),
            self::VIDEO_TYPE_EMBEDDED    => $this->__('Embed Code'),
        );
    }

    /**
     * @return int
     */
    public function getVideoWidth()
    {
        return $this->_videoWidth;
    }

    /**
     * @return int
     */
    public function getVideoHeight()
    {
        return$this->_videoHeight;
    }

    /**
     * @param $value
     * @param bool $forceDownload
     * @return string
     */
    public function getYouTubeImage($value, $forceDownload = false)
    {
        $baseDir   = Mage::getBaseDir('media');
        $localDir = 'gallery' . DS . 'data' . DS . 'thumbnail';
        if (is_dir($localDir)) {
            mkdir($localDir, 0777, true);
        }

        $fileName = Varien_File_Uploader::getNewFileName($baseDir . DS . $localDir  . DS . $value . '.jpg');
        $absPath  = $baseDir . DS . $localDir  . DS . $fileName;

        if ($forceDownload || !is_file($absPath)) {
            try {
                $url       = sprintf('http://img.youtube.com/vi/%s/0.jpg', $value);
                $client    = new Zend_Http_Client($url);
                $response  = $client->request(Zend_Http_Client::GET);
                if (200 != $response->getStatus())  {
                    Mage::throwException("Can't download youtube thumbnail");
                }
                if (!is_dir($baseDir . DS . $localDir)) {
                    mkdir($baseDir . DS . $localDir, 0777, true);
                }
                file_put_contents($absPath, $response->getBody());
            } catch (Exception $e) {
                Mage::logException($e);
                return false;
            }
        }

        return $localDir  . DS . $fileName;
    }

    /**
     * @param ICC_Vchannel_Model_Item $item
     * @param int|null $width
     * @param int|null $height
     * @return string
     */
    public function getVideoHtml(ICC_Vchannel_Model_Item $item, $width = null, $height = null)
    {
        $html   = '';
        $width  = ($width  > 0 ? intval($width)  : $this->getVideoWidth());
        $height = ($height > 0 ? intval($height) : $this->getVideoHeight());;
        switch ($item->getData('additional/type')) {
            case self::VIDEO_TYPE_EMBEDDED:
                $html = $item->getData('value');
                $html = preg_replace('/width\=[\"\']{0,1}\d+[\"\']{0,1}/si',  sprintf('width="%d"', $width), $html);
                $html = preg_replace('/height\=[\"\']{0,1}\d+[\"\']{0,1}/si', sprintf('height="%d"', $height), $html);
                break;
            case self::VIDEO_TYPE_FILE:
                $html     = '<div id="container">'. $this->__('Loading the player ...') .'</div>'
                    . '<script type="text/javascript">'
                    . 'jwplayer("container").setup({'
                    //. 'flashplayer: "' . $this->getPlayerUrl() . '",'
                    . 'file: "'  . $this->getVideoFileUrl($item) .'",'
                    . 'image: "'  . $this->getImageUrl($item, 'thumbnail') .'",'
                    . 'width: "'  . $width  . '",'
                    . 'height: "' . $height . '",'
                    //. 'skin: "'   . $this->getPlayerSkinUrl() . '"'
                    . '});'
                    . '</script>';
                break;
            case self::VIDEO_TYPE_YOUTUBE:
                $html     = '<iframe
                    style="'  . $width   . 'px;height:' . $height . 'px;"
                    frameborder="0"
                    src="' . sprintf('http://www.youtube.com/embed/%s', $item->getData('value')) . '"
                    width="'  . $width  . '"
                    height="' . $height . '"></iframe>';
                break;
        }

        return $html;
    }

    /**
     * @param ICC_Vchannel_Model_Item $item
     * @return string
     */
    public function getVideoFileUrl(ICC_Vchannel_Model_Item $item)
    {
        return Mage::getBaseUrl('media') .  $item->getData('value');
    }

    /**
     * @return string
     */
    public function getPlayerUrl()
    {
        return Mage::getBaseUrl('media') . 'gallery' . DS . 'player.swf';
    }

    /**
     * @return string
     */
    public function getPlayerSkinUrl()
    {
        return Mage::getBaseUrl('media') . 'videogallery' . DS . 'skin' . DS . 'modieus.zip';
    }

    /**
     * @param ICC_Vchannel_Model_Item $item
     * @param Varien_Data_Form $form
     * @return $this|ICC_Vchannel_Helper_Item_Interface
     */
    public function prepareEditForm(ICC_Vchannel_Model_Item $item, Varien_Data_Form $form)
    {
        $isReadonlyMode = false;
        foreach ($form->getElements() as $element) {
            /** @var Varien_Data_Form_Element_Abstract $element */
            if ($element instanceof Varien_Data_Form_Element_Fieldset
                && 'general_information' == $element->getId()) {
                $fieldSet = $element;
                $fieldSet->addField('additional_type', 'select', array(
                    'name'      => 'item[additional][type]',
                    'label'     => $this->__('Source Type'),
                    'title'     => $this->__('Source Type'),
                    'required'  => true,
                    'disabled'  => $isReadonlyMode,
                    'options'   => Mage::getModel('icc_vchannel/config_system_source_type')->toOptionArray(),
                ));

                $fieldSet->addField('additional_value_file', 'file', array(
                    'name'      => 'item[additional_value_file]',
                    'label'     => $this->__('Video File'),
                    'title'     => $this->__('Video File'),
                    'required'  => !strlen($item->getData('value')),
                    'disabled'  => $isReadonlyMode,
                    'container_id' => 'container_value_file',
                    'note'         => $this->__('Allowed format(s): <strong>%s</strong>', implode(', ', $item->getAllowedFormats()))
                        . '<br/>'
                        . $this->__('Your server allow you to upload files not more than <strong>%s</strong>. You can modify <strong>post_max_size</strong> (currently is %s) and <strong>upload_max_filesize</strong> (currently is %s) values in php.ini if you want to upload larger files.', $this->getDataMaxSize(), $this->getPostMaxSize(), $this->getUploadMaxSize()),
                ));

                $fieldSet->addField('additional_value_youtube', 'text', array(
                    'name'      => 'item[additional][value_youtube]',
                    'label'     => $this->__('YouTube ID'),
                    'title'     => $this->__('YouTube ID'),
                    'required'  => true,
                    'disabled'  => $isReadonlyMode,
                    'container_id' => 'container_value_youtube',
                ));

                $fieldSet->addField('additional_value_embedded', 'textarea', array(
                    'name'      => 'item[additional][value_embedded]',
                    'label'     => $this->__('Embed Code'),
                    'title'     => $this->__('Embed Code'),
                    'required'  => true,
                    'disabled'  => $isReadonlyMode,
                    'container_id' => 'container_value_embedded',
                ));

                break;
            }
        }

        return $this;
    }

    /**
     * ICC_Vchannel_Model_Item $item
     */
    public function prepareEditFormScripts(ICC_Vchannel_Model_Item $item, array $scripts)
    {
        $scripts[] = '
        var VideoType = Class.create({
            element:null,
            currentFieldName:null,
            initialize: function(element) {
                this.element    = element;
                this.currentFieldName = element.getAttribute("value");
                this.switch();
                Event.observe(element, "change", this.switch.bind(this));
            },
            switch:function () {
                switch (this.element.value) {
                    case "' . ICC_Vchannel_Helper_Item_Video::VIDEO_TYPE_YOUTUBE . '":
                        $("container_value_file").addClassName("no-display");
                        $("container_value_embedded").addClassName("no-display");
                        $("container_value_youtube").removeClassName("no-display");
                        $("additional_value_file").removeClassName("required-entry");
                        $("additional_value_embedded").removeClassName("required-entry");
                        if (this.currentFieldName != "' . ICC_Vchannel_Helper_Item_Video::VIDEO_TYPE_YOUTUBE . '") {
                            $("additional_value_youtube").addClassName("required-entry");
                        }
                        break;
                    case "' . ICC_Vchannel_Helper_Item_Video::VIDEO_TYPE_FILE . '":
                        $("container_value_file").removeClassName("no-display");
                        $("container_value_youtube").addClassName("no-display");
                        $("container_value_embedded").addClassName("no-display");
                        if (this.currentFieldName != "' . ICC_Vchannel_Helper_Item_Video::VIDEO_TYPE_FILE . '") {
                            //$("additional_value_file").addClassName("required-entry");
                        }
                        $("additional_value_youtube").removeClassName("required-entry");
                        $("additional_value_embedded").removeClassName("required-entry");
                        break;
                    case "' . ICC_Vchannel_Helper_Item_Video::VIDEO_TYPE_EMBEDDED . '":
                        $("container_value_embedded").removeClassName("no-display");
                        $("container_value_youtube").addClassName("no-display");
                        $("container_value_file").addClassName("no-display");
                        if (this.currentFieldName != "' . ICC_Vchannel_Helper_Item_Video::VIDEO_TYPE_EMBEDDED . '") {
                            $("additional_value_embedded").addClassName("required-entry");
                        }
                        $("additional_value_youtube").removeClassName("required-entry");
                        $("additional_value_file").removeClassName("required-entry");
                        break;
                    default:
                        $("container_value_file").addClassName("no-display");
                        $("container_value_youtube").addClassName("no-display");
                        $("container_value_embedded").addClassName("no-display");
                        if (this.isRequired) {
                            $("additional_value_file").removeClassName("required-entry");
                            $("additional_value_youtube").removeClassName("required-entry");
                            $("additional_value_embedded").removeClassName("required-entry");
                        }
                        break;
                }
            }
        });
        var videoType = new VideoType($("additional_type"));
        ';

        return $scripts;
    }

    /**
     * ICC_Vchannel_Model_Item $item
     */
    public function prepareItemSave(ICC_Vchannel_Model_Item $item, Mage_Adminhtml_Controller_Action $controller)
    {
        parent::prepareItemSave($item, $controller);

        $data = $controller->getRequest()->getPost('item');
        $additional = isset($data['additional']) && is_array($data['additional']) ? $data['additional'] : array();

        switch ($additional['type']) {
            case self::VIDEO_TYPE_FILE:
                if (isset($data['additional_value_file'], $data['additional_value_file'], $data['additional_value_file']['delete']) && !empty($data['additional_value_file']['delete'])) {
                    $item->deleteValueFile();
                } else if(
                    isset($_FILES['item']['tmp_name']['additional_value_file'])
                    && $_FILES['item']['tmp_name']['additional_value_file']
                ) {
                    $savedFilePath = $this->_saveFile('item[additional_value_file]', $item->getAllowedFormats(), 'video');
                    $additional['value_file'] = $savedFilePath;
                    $item->setData('value', $savedFilePath);
                }
                break;
            case self::VIDEO_TYPE_EMBEDDED:
                if (array_key_exists('value_embedded', $additional)) {
                    $item->setData('value', $additional['value_embedded']);
                }
                break;
            case self::VIDEO_TYPE_YOUTUBE:
                if (array_key_exists('value_youtube', $additional)) {
                    $item->setData('value', $additional['value_youtube']);
                }

                if (!$item->getData('thumbnail')) {
                    if ($thumbnail = $this->getYouTubeImage($item->getData('value'))) {
                        $item->setData('thumbnail', $thumbnail);
                    }
                }

                break;

        }

        $item->setData('additional', $additional);

        return $this;
    }
}
