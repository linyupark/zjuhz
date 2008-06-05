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
			
			// 分配当前角色信息
			$this->view->role = $this->_sessCommon->role;
			
			// 分配角色详细信息
			$this->view->login = $this->_sessCommon->login;
			
		}
		
		#每个栏目的详细列表
		function indexAction()
		{
			//创建对象
			$Category = new CategoryModel();
			$Db = Zend_Registry::get('dbInfo');
			
			//获取当前需要显示的分类列表
			$categoryId = (int)$this->_getParam('category',0);
			if($categoryId == 0) $categoryId = $Category->fetchRow()->category_id;
			
			$this->view->categoryId = $categoryId;
			
			$row = $Db->fetchRow('SELECT COUNT(`entity_id`) AS `numrows` FROM `tbl_entity` WHERE `category_id` = ?', $categoryId);
			
			//按页获取信息列表
			Page::create(array(
			"href_open" => "<a href='/info/?category={$categoryId}&p=%d'>", 
			"href_close" => "</a>", 
			"num_rows" => $row['numrows'],
			"cur_page" => $this->_getParam('p',1)));
			
			// 一些统计
			$this->view->numrows = $row['numrows'];
			$this->view->numpages = Page::$num_pages;
			$this->view->curPage = Page::$_set['cur_page'];
			
			$this->view->pagination = Page::$page_str;
												  
			$this->view->rows = $Db->fetchAll('SELECT `entity_id`,`entity_title`,`entity_pub_time`,`entity_top`,`user_name`
			                                   FROM `vi_entity` WHERE `category_id` = ? AND `entity_pub` = 1 
			                                   ORDER BY `entity_top` DESC ,`entity_pub_time` DESC, `entity_id` DESC 
			                                   LIMIT '.Page::$offset.','.Page::$pagesize, $categoryId);
												  
			//分类列表
			$this->view->categories = $Category->fetchAll();
			
		}
		
		#验证图片
		function verifyAction()
		{
			$this->_helper->layout->disableLayout();
			$this->_helper->ViewRenderer->setNoRender();
			ImageHandle::verify('common');
		}
	}