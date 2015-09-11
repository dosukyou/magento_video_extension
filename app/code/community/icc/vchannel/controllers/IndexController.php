<?php
/**
 *
 * open license
 */

class ICC_Vchannel_IndexController
    extends ICC_Vchannel_Controller_Abstract
{
    /**
     * Gallery Home
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
}
