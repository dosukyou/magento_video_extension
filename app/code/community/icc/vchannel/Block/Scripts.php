<?php
/**
 * open license
 */

class ICC_Vchannel_Block_Scripts
    extends ICC_Vchannel_Block_Abstract
{
    /**
     * @return string
     */
    public function getRequireDirUrl()
    {
        return Mage::getBaseUrl('js') . 'icc';
    }

    /**
     * @return string
     */
    public function getRequireUrl()
    {
        return Mage::getBaseUrl('js') . 'icc/require.js';
    }

    /**
     * @return string
     */
    public function getBootstrapUrl()
    {
        return Mage::getBaseUrl('js') . 'icc/vchannel/bootstrap';
    }
}
