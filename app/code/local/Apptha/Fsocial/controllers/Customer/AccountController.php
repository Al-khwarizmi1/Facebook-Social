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
class Apptha_Fsocial_Customer_AccountController extends Mage_Core_Controller_Front_Action {

    public $appId;
    public $myUrl;
    public $appSecret;

    /* get the appid and secret key from the admin configuration */

    public function _construct() {
        parent::_construct();
        $this->appId = Mage::getStoreConfig('fsocial/application/apiid');
        $this->appSecret = Mage::getStoreConfig('fsocial/application/apikey');
        require_once 'fsocial/facebook.php';
    }

    public function connectAction() {


        $facebook = new Facebook(array(
                    'appId' => "$this->appId",
                    'secret' => "$this->appSecret",
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

        if ($user) {

            $checkIfUserLikePage = $facebook->api(array(
                "method" => "fql.query",
                "query" => "select first_name,last_name,email from user where uid=me()"
                    ));

            $customer = Mage::getModel('customer/customer');
            /* check the user stands on facebook site */

            if ($checkIfUserLikePage[0]['email']) {
                $customer->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                        ->loadByEmail($checkIfUserLikePage[0]['email']);
                if ($customer->getId()) {
                    $this->getcustomer_session()->setCustomerAsLoggedIn($customer);
                } else {
                    $randomPassword = $customer->generatePassword(8);
                    $customer->setId(null)
                            ->setSkipConfirmationIfEmail($checkIfUserLikePage[0]['email'])
                            ->setFirstname($checkIfUserLikePage[0]['first_name'])
                            ->setLastname($checkIfUserLikePage[0]['last_name'])
                            ->setEmail($checkIfUserLikePage[0]['email'])
                            ->setPassword($randomPassword)
                            ->setConfirmation($randomPassword)
                            ->setFacebookUid($facebook->getUser());
                    $customer->save();
                    $this->getcustomer_session()->setCustomerAsLoggedIn($customer);
                }
            }
        }
        $this->_loginPostRedirect();
    }

     /**
     * Define target URL and redirect customer after logging in
     */
    protected function _loginPostRedirect()
    {
        $session = $this->getcustomer_session();

        if (!$session->getBeforeAuthUrl() || $session->getBeforeAuthUrl() == Mage::getBaseUrl()) {

            // Set default URL to redirect customer to
            $session->setBeforeAuthUrl(Mage::helper('customer')->getAccountUrl());
            // Redirect customer to the last page visited after logging in
            if ($session->isLoggedIn()) {
                if (!Mage::getStoreConfigFlag('customer/startup/redirect_dashboard')) {
                    $referer = $this->getRequest()->getParam(Mage_Customer_Helper_Data::REFERER_QUERY_PARAM_NAME);
                    if ($referer) {
                        $referer = Mage::helper('core')->urlDecode($referer);
                        if ($this->_isUrlInternal($referer)) {
                            $session->setBeforeAuthUrl($referer);
                        }
                    }
                } else if ($session->getAfterAuthUrl()) {
                    $session->setBeforeAuthUrl($session->getAfterAuthUrl(true));
                }
            } else {
                $session->setBeforeAuthUrl(Mage::helper('customer')->getLoginUrl());
            }
        } else if ($session->getBeforeAuthUrl() == Mage::helper('customer')->getLogoutUrl()) {
            $session->setBeforeAuthUrl(Mage::helper('customer')->getDashboardUrl());
        } else {
            if (!$session->getAfterAuthUrl()) {
                $session->setAfterAuthUrl($session->getBeforeAuthUrl());
            }
            if ($session->isLoggedIn()) {
                $session->setBeforeAuthUrl($session->getAfterAuthUrl(true));
            }
        }
        $this->_redirectUrl($session->getBeforeAuthUrl(true));
    }
    /* Get the customer session */

    private function getcustomer_session() {
        return Mage::getSingleton('customer/session');
    }

}
