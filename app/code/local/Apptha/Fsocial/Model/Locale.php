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
class Apptha_Fsocial_Model_Locale {

    public function toOptionArray() {
        return $this->getOptionLocales();
    }

    public function getLocales() {
        $locales = array();
        $localesFile = Mage::app()->getConfig()->getModuleDir('etc', 'Apptha_Fsocial') . DS . 'FacebookLocales.xml';

        $xml = simplexml_load_file($localesFile, null, LIBXML_NOERROR);
        if ($xml && is_object($xml->locale)) {
            foreach ($xml->locale as $item) {
                $locales[(string) $item->codes->code->standard->representation] = (string) $item->englishName;
            }
        }

        asort($locales);
        return $locales;
    }

    public function getOptionLocales() {
        $locales = array();
        foreach ($this->getLocales() as $value => $label) {
            $locales[] = array(
                'value' => $value,
                'label' => $label
            );
        }
        return $locales;
    }

}