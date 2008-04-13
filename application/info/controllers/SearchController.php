<?php
	
	/**
	 * 资讯全文索引查询
	 *
	 */
	class SearchController extends Zend_Controller_Action 
	{
		function init()
		{
			$this->view->headLink()->appendStylesheet('/static/styles/home.css','screen')
								   ->appendStylesheet('/static/styles/info_front.css','screen');

			// 获取全局SESSION 
			$this->_sessCommon = Zend_Registry::get('sessCommon');
								   
			// 当前所属模块分配
			$this->view->request = $this->getRequest();
			
			// 分配当前角色信息
			$this->view->role = $this->_sessCommon->role;
			
			// 分配角色详细信息
			$this->view->login = $this->_sessCommon->login;
		}
		
		# 查找
		function lookAction()
		{
			if($keywords = $this->getRequest()->getParam('for'))
			{
				$pageNum = $this->getRequest()->getParam('p',1);
				$Db = new DbModel();
				$rows = $Db->getSearchResult($keywords,($pageNum-1)*10);
				//按页获取信息列表
				Page::create(array(
				"href_open" => "<a href='/info/search/look/for/{$keywords}/p/%d'>", 
				"href_close" => "</a>", 
				"num_rows" => $rows['numrows'],
				"cur_page" => $pageNum));
				
				$this->view->p = $pageNum;
				$this->view->pagination = Page::$page_str;
				$this->view->numrows = $rows['numrows'];
				$this->view->rows = $rows['results'];
				$this->view->keywords = urldecode($keywords);
			}
		}
		
	}