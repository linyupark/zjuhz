<?php

class IndexController extends Zend_Controller_Action
{
	function init()
	{
		$this->view->headScript()->appendFile('/static/scripts/home.js');
		$this->view->headLink()->appendStylesheet('/static/styles/home.css','screen');
		
		$this->_sessCommon = Zend_Registry::get('sessCommon');
		// 分配角色详细信息
		$this->view->login = $this->_sessCommon->login;
		if($this->view->login['uid'] != null) $this->getRequest()->setControllerName('welcome');
	}
	
    function indexAction()
    {
        $this->view->Group = new Group();
        $this->view->Biz = new Biz();
    	$this->view->uname = $_COOKIE['zjuhz_member']['uname']; // 记住账号
        $this->view->pswd = Commons::decrypt($_COOKIE['zjuhz_member']['pswd']); // 记住账号
		$this->view->cache = CacheModel::init(null,600);
    	$this->view->info_xmlrpc = new Zend_XmlRpc_Client('http://xmlrpc/InfoServer.php');
		$this->render('v2');
    }
}
