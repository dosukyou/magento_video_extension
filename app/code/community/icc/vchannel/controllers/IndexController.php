<?php
/**
 * @copyright Copyright (c) 2014 Sergey Cherepanov (sergey@cherepanov.org.ua)
 * @license Creative Commons Attribution 3.0 License
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
