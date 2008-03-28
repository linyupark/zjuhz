<?php

class IndexController extends Zend_Controller_Action
{
	function init()
	{
		$this->_helper->layout()->setLayout('main');
		$this->view->headScript()->appendFile('/static/scripts/jquery.js')
								 ->appendFile('/static/scripts/home.js');
		$this->view->headLink()->appendStylesheet('/static/styles/home.css','screen');
	}
    function indexAction()
    {
    	$flag = $this->getRequest()->getParam('f');
    	$this->view->status = '您目前尚未登陆';
    	if($flag == 'in')
    	{
    		$this->view->status = 'xxx';
    		$this->render('index2');
    	}
    	$client = new Zend_XmlRpc_Client('http://group.zjuhz.com/static/xmlrpc/Info.php');
    	echo $client->call('md5Value',array('123123'));
    }
}
