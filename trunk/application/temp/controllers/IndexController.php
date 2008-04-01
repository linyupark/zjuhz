<?php

class IndexController extends Zend_Controller_Action
{
	function init()
	{
		$this->view->headScript()->appendFile('/static/scripts/temp.js');
		$this->view->headLink()->appendStylesheet('/static/styles/temp.css','screen');
		$this->view->headTitle('浙大校友互助');
		$this->_helper->layout->setLayout('main');
		
		//当前所属模块分配
		$this->view->header = array('model_name'=>'help');
		
		$this->view->role = 'member';
		$this->view->account_info = array(
    		'name'=>'小王',
    		'letter'=>'2'
    	);
	}
	
    function indexAction()
    {
    	
    }
}
