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
class Apptha_Fsocial_Model_Font extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('fsocial/font');
    }
    public function toOptionArray()
    {
        $ajaxpages = array('arial', 'lucida grande', 'segoe ui', 'tahoma','trebuchet ms','verdana');
        $temp = array();

        foreach($ajaxpages as $ajaxpage)	{
            $temp[] = array('label' => $ajaxpage, 'value' => strtolower($ajaxpage));
        }

        return $temp;
    }
}