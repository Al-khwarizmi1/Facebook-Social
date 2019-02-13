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
class Apptha_Fsocial_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/fsocial?id=15 
    	 *  or
    	 * http://site.com/fsocial/id/15 	
    	 */
    	/* 
		$fsocial_id = $this->getRequest()->getParam('id');

  		if($fsocial_id != null && $fsocial_id != '')	{
			$fsocial = Mage::getModel('fsocial/fsocial')->load($fsocial_id)->getData();
		} else {
			$fsocial = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($fsocial == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$fsocialTable = $resource->getTableName('fsocial');
			
			$select = $read->select()
			   ->from($fsocialTable,array('fsocial_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$fsocial = $read->fetchRow($select);
		}
		Mage::register('fsocial', $fsocial);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}