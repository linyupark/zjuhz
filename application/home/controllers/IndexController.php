<?php

class IndexController extends Zend_Controller_Action
{
	function init()
	{
		$this->_helper->layout()->setLayout('main');
		$this->view->headScript()->appendFile('/static/scripts/home.js');
		$this->view->headLink()->appendStylesheet('/static/styles/home.css','screen');
	}
	
    function indexAction()
    {
    	$needClear = $this->getRequest()->getParam('do');
    	
    	$frontendOptions = array(
   			'lifetime' => 999999,                  // cache lifetime of half a minute
   			'automatic_serialization' => false  // this is default anyway
		);

		$backendOptions = array('cache_dir' => '../../cache/');

		$cache = Zend_Cache::factory('Output', 'File', $frontendOptions, $backendOptions);
		
		if($needClear == 1) $cache->remove('homepage');
		
		$this->view->cache = $cache;
    	$this->view->role = 'guest';
    	$this->view->info_xmlrpc = new Zend_XmlRpc_Client('http://zjuhz/xmlrpc/InfoServer.php');
    }
}
