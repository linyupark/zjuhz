<?php

class WelcomeController extends Zend_Controller_Action
{
	function init()
	{
		$this->view->headScript()->appendFile('/static/scripts/home.js');
		$this->view->headLink()->appendStylesheet('/static/styles/home.css','screen');
		$this->_sessCommon = Zend_Registry::get('sessCommon');
		// 分配角色详细信息
		$this->view->login = $this->_sessCommon->login;
		$this->view->request = $this->getRequest();
	}
	
    function indexAction()
    {
		$this->view->Group = new Group();
        $this->view->Biz = new Biz();
    	$this->view->cache = CacheModel::init(null,600);
    	$this->view->info_xmlrpc = new Zend_XmlRpc_Client('http://xmlrpc/InfoServer.php');
    }
}
