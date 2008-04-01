<?php

class IndexController extends Zend_Controller_Action
{
	function init()
	{
		$this->_helper->layout()->setLayout('main');
		$this->view->headScript()->appendFile('/static/scripts/home.js');
		$this->view->headLink()->appendStylesheet('/static/styles/home.css','screen');
		
		//当前所属模块分配
		$this->view->header = array('model_name'=>'info');
	}
	
    function indexAction()
    {
    	$this->view->role = 'guest';
    	$this->view->info_xmlrpc = new Zend_XmlRpc_Client('http://zjuhz/info/api/');
    }
}