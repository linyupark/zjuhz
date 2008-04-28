<?php

class WelcomeController extends Zend_Controller_Action
{
	function init()
	{
		$this->_helper->layout()->setLayout('main');
		$this->view->headScript()->appendFile('/static/scripts/home.js');
		$this->view->headLink()->appendStylesheet('/static/styles/home.css','screen');
		$this->_sessCommon = Zend_Registry::get('sessCommon');
		// 分配角色详细信息
		$this->view->login = $this->_sessCommon->login;
		$this->view->request = $this->getRequest();
	}
	
    function indexAction()
    {
    	$frontendOptions = array(
   			'lifetime' => 99999999,             // cache lifetime of half a minute
   			'automatic_serialization' => false  // this is default anyway
		);

		$backendOptions = array('cache_dir' => '../../cache/');

		$this->view->cache = Zend_Cache::factory('Output', 'File', $frontendOptions, $backendOptions);
		$this->view->info_xmlrpc = new Zend_XmlRpc_Client('http://zjuhz/info/api/');
    	$this->view->role = 'member';
    	
    	$this->view->login = $this->_sessCommon->login;
    }
}
