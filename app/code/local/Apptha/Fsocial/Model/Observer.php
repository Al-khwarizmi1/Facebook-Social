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

class Apptha_Fsocial_Model_Observer extends Varien_Object {

    public $appId;
    public $appSecret;


    /* get the appid and secret key from the admin configuration */

    public function _construct() {
        parent::_construct();
    }

    public function publishwall($observer) {

        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('read');
        $tPrefix = (string) Mage::getConfig()->getTablePrefix();
        $quoteItemTable = $tPrefix . 'sales_flat_quote_item';
        $quoteId = Mage::getSingleton('checkout/session')->getLastQuoteId();
        $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();


        $selectQuote = $read->select()
                        ->from(array('ct' => $quoteItemTable), array('ct.*'))
                        ->where('ct.quote_id =? ', $quoteId);
        $customerQuote = $read->fetchAll($selectQuote);


        $order = Mage::getModel('sales/order')->load($orderId);
        $items = $order->getAllItems();

        $itemcount = count($items);

        $appId = Mage::getStoreConfig('fsocial/application/apiid');
        $appSecret = Mage::getStoreConfig('fsocial/application/apikey');
        require_once 'fsocial/facebook.php';

        $facebook = new Facebook(array(
                    'appId' => "$appId",
                    'secret' => "$appSecret",
                    'cookie' => true,
                ));


// Get User ID
        $user = $facebook->getUser();


        if ($user) {
            try {
                // Proceed knowing you have a logged in user who's authenticated.
                $user_profile = $facebook->api('/me');
            } catch (FacebookApiException $e) {
                error_log($e);
                $user = null;
            }
        }


        $store = Mage::app()->getStore();
        $app_name = $store->getName();

        $wallMain = Mage::getStoreConfig('fsocial/wallpost/wallmain');
        $postImage = Mage::getStoreConfig('fsocial/wallpost/postimage');
        $template = Mage::getStoreConfig('fsocial/wallpost/template');


        $template = str_replace('{item_count}', $itemcount, $template);

        $noitem = Mage::getStoreConfig('fsocial/wallpost/noitem');
        $count = 1;
        if ($wallMain == '1') {
            foreach ($items as $itemId => $item) {
                $actionTemplate = '';
                $_product = Mage::getModel('catalog/product')->load($item->getProductId());
                $actionTemplate = Mage::getStoreConfig('fsocial/wallpost/actiontemplate');
                $actionTemplate = str_replace('{item_name}', $_product->getName(), $actionTemplate);
                $actionTemplate = str_replace('{item_price}', $_product->getPrice(), $actionTemplate);

                if ($_product->getStatus() == 1) {

                    $producturl = $_product->getProductUrl();
                    $product_url = str_replace("?___SID=U", "", $producturl);
                }
                //$myUrl = Mage::getBaseUrl() . $product_url;
                $actionTemplate = str_replace('{item_link}', $product_url, $actionTemplate);
                $storeUrl = Mage::getBaseUrl();
                $template = str_replace('{store_link}', $storeUrl, $template);
                $actionTemplate = str_replace('{store_link}', $storeUrl, $actionTemplate);
                $actionTemplate = str_replace('{item_count}', $itemcount, $actionTemplate);
                $product_description = $actionTemplate;
                $product_name = $_product->getName();
                $picture = Mage::helper('catalog/image')->init($_product, 'small_image');
                if (empty($picture)) {
                    $picture = 'http://fbrell.com/f8.jpg';
                }
                if ($user) {

                    $fbToken = $facebook->getAccessToken();
                    if ($postImage == '1') {
                        $attachment = array(
                            'description' => $product_description,
                            'access_token' => $fbToken,
                            'picture' => "$picture",
                        	'link' => "$product_url",
                            'message' => $template,
                        );
                    } else {
                        $attachment = array(
                            'description' => $product_description,
                            'access_token' => $fbToken,
                        	'link' => "$product_url",
                            'message' => $template,
                        );
                    }
                    /* api to post the product in the wall */
                    $result = $facebook->api('/me/feed/', 'post', $attachment);
                }
                if ($count == $noitem) {
                    break;
                }
                $count++;
            }
        }
        return;
    }

}