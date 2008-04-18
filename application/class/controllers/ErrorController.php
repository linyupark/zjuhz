<?php

class ErrorController extends Zend_Controller_Action
{
	
	function init()
	{
		// 注册全局SESSION
		$this->_sessCommon = Zend_Registry::get('sessCommon');
		$this->view->login = Zend_Registry::get('sessCommon')->login;
	}
	
    function errorAction()
    {
    	$this->view->message = $this->getRequest()->getParam('message');
    }
    
    function reloginAction()
    {
    	
    }
}
