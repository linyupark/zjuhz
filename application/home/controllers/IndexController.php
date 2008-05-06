<?php

class IndexController extends Zend_Controller_Action
{
	function init()
	{
		$this->_helper->layout()->setLayout('main');
		$this->view->headScript()->appendFile('/static/scripts/home.js');
		$this->view->headLink()->appendStylesheet('/static/styles/home.css','screen');
		
		$this->_sessCommon = Zend_Registry::get('sessCommon');
		// 分配角色详细信息
		$this->view->login = $this->_sessCommon->login;
		if($this->view->login['uid'] != null) $this->getRequest()->setControllerName('welcome');
	}
	
    function indexAction()
    {	
		$this->view->cache = CacheModel::init(null,99999);
    	$this->view->info_xmlrpc = new Zend_XmlRpc_Client('http://xmlrpc/InfoServer.php');
    }
}
