<?php

class HomeController extends Zend_Controller_Action
{
	function init()
	{
		// 注册全局SESSION
		$this->_sessCommon = Zend_Registry::get('sessCommon');
		$this->_sessClass = Zend_Registry::get('sessClass');
		$this->view->login = Zend_Registry::get('sessCommon')->login;
	}
	
	function indexAction()
	{
		$this->view->class = $this->_sessClass->data;
	}
}
