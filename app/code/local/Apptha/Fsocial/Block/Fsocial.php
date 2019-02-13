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
class Apptha_Fsocial_Block_Fsocial extends Mage_Core_Block_Template
{
 protected function _prepareLayout()
    {
        $block = $this->getLayout()->getBlock('checkout.onepage.login.before');
        if ($block) {
            $block->setTemplate('fsocial/checkoutlogin.phtml');
        }
    }
    
     public function getFsocial()     
     { 
        if (!$this->hasData('fsocial')) {
            $this->setData('fsocial', Mage::registry('fsocial'));
        }
        return $this->getData('fsocial');
        
    }
}