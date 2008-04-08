<?php

	class IndexController extends Zend_Controller_Action 
	{
		function init()
		{
			$this->view->headTitle('信息中心');
			$this->view->headLink()->appendStylesheet('/static/styles/home.css','screen')
								   ->appendStylesheet('/static/styles/info_front.css','screen');

			// 获取全局SESSION 
			$this->_sessCommon = Zend_Registry::get('sessCommon');
								   
			// 当前所属模块分配
			$this->view->request = $this->getRequest();
			
			// 分配当前角色信息
			$this->view->role = $this->_sessCommon->role;
			
			// 分配角色详细信息
			$this->view->accountInfo = array(
    	    	'realName'=>$this->_sessCommon->login['realName'],
    	    	'unRead'=>'0',
    		);
		}
		
		#每个栏目的详细列表
		function indexAction()
		{
			//创建对象
			$Category = new CategoryModel();
			$Entity = new EntityModel();
			
			//获取当前需要显示的分类列表
			$categoryId = $this->_getParam('category',0);
			if($categoryId == 0) $categoryId = $Category->fetchRow()->category_id;
			
			$this->view->categoryId = $categoryId;
			
			$rowSet = $Entity->fetchAll('category_id = '.$categoryId);
			
			//按页获取信息列表
			Page::create(array(
			"href_open" => "<a href='/info/?category={$categoryId}&p=%d'>", 
			"href_close" => "</a>", 
			"num_rows" => count($rowSet),
			"cur_page" => $this->_getParam('p',1)));
			
			$this->view->pagination = Page::$page_str;
			
			//根据分页获取相关信息
			$this->view->rows = $Entity->fetchAll('category_id = '.$categoryId, 
												  'entity_pub_time DESC', Page::$pagesize, Page::$offset);
												  
			//分类列表
			$this->view->categories = $Category->fetchAll();
			
		}
	}