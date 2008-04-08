<?php

class IndexController extends Zend_Controller_Action
{
	function init()
	{
		$this->view->headScript()->appendFile('/static/scripts/temp.js');
		$this->view->headLink()->appendStylesheet('/static/styles/temp.css','screen');

		//选择页面模块
		$this->view->request = $this->getRequest();
		//权限资料注入
		$this->view->role = $this->_sessCommon->role;
		$this->view->login = $this->_sessCommon->login;
	}
	
	// 首页
    function indexAction()
    {
    	
    }
    
    // 管理首页
    function homeAction()
    {
    	
    }
    
    //问题详细页
    function detailAction()
    {
    	
    }
    
    function detailokAction()
    {
    }
    
    // 子目录主页
    function subAction()
    {
    }
}
