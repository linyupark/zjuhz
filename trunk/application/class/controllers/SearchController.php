<?php

	class SearchController extends Zend_Controller_Action 
	{
		function init()
		{
			// 注册全局SESSION
			$this->_sessCommon = Zend_Registry::get('sessCommon');
			$this->view->login = $this->_sessCommon->login;
		}
		
		function indexAction()
		{
			$keywords = trim($this->_getParam('for'));
			$year = $this->_getParam('year');
			$college = $this->_getParam('college');
			$page = $this->_getParam('p',1);
			
			//按页获取信息列表
			Page::$pagesize = 20;
			$rows = DbModel::searchClass($keywords, $year, $college, ($page-1)*Page::$pagesize,Page::$pagesize);
			Page::create(array(
			"href_open" => "<a href='/class/?for={$keywords}&p=%d'>", 
			"href_close" => "</a>", 
			"num_rows" => $rows['numrows'],
			"cur_page" => $page));
			
			// 获取会员所申请加入班级的信息
			$this->view->applies = DbModel::getClassApply($this->view->login['uid']);
			
			// 分配班级数据
			$this->view->year = $year;
			$this->view->college = $college;
			$this->view->class_rows = $rows['rows'];
			$this->view->class_num = $rows['numrows'];
			$this->view->pagination = Page::$page_str;
			
			$this->view->keywords = $keywords;
		}
	}