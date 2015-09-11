<?php
/**
 * Open License
 */

abstract class ICC_Vchannel_Controller_Abstract
    extends Mage_Core_Controller_Front_Action
{
    /**
     * Prepare menu and handles
     */
    public function addActionLayoutHandles()
    {
        parent::addActionLayoutHandles();

        $update = $this->getLayout()->getUpdate();
        $update->addHandle('icc_vchannel');
        $update->addHandle('icc_vchannel_scripts');
    }
}
