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
class Apptha_Fsocial_Model_Config {
    const XML_PATH_ENABLED = 'fsocial/general/main';
    const XML_PATH_API_KEY = 'fsocial/application/apiid';
    const XML_PATH_SECRET = 'fsocial/application/apikey';
    const XML_PATH_LOCALE = 'fsocial/application/locale';

    public function isEnabled($storeId=null) {

        if (Mage::getStoreConfigFlag(self::XML_PATH_ENABLED, $storeId) &&
                $this->getApiID($storeId) &&
                $this->getSecretKey($storeId)) {
            return true;
        }

        return false;
    }

    public function getApiID($storeId=null) {
        return trim(Mage::getStoreConfig(self::XML_PATH_API_KEY, $storeId));
    }

    public function getSecretKey($storeId=null) {
        return trim(Mage::getStoreConfig(self::XML_PATH_SECRET, $storeId));
    }

    public function getLocale($storeId=null) {
        return Mage::getStoreConfig(self::XML_PATH_LOCALE, $storeId);
    }

}
