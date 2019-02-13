<?php
/**
 * @name         :  Facebook Social
 * @version      :  1.0
 * @since        :  Magento 1.4
 * @author       :  Apptha - http://www.apptha.com
 * @copyright    :  Copyright (C) 2011 Powered by Apptha
 * @license      :  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Creation Date:  October 22 2011
 * 
 * */
class Apptha_Fsocial_Block_Template extends Mage_Core_Block_Template {

    public function getConnectUrl() {
        return $this->getUrl('fsocial/customer_account/connect', array('_secure' => true));
    }

    public function isEnabled() {
        return Mage::getSingleton('fsocial/config')->isEnabled();
    }

    public function getApiID() {
        return Mage::getSingleton('fsocial/config')->getApiID();
    }

    public function getLocale() {
        return Mage::getSingleton('fsocial/config')->getLocale();
    }

    protected function _toHtml() {
        if (!$this->isEnabled()) {
            return '';
        }
        return parent::_toHtml();
    }

}