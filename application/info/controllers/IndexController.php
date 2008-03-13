<?php

	class IndexController extends Zend_Controller_Action 
	{
		function init()
		{
			$this->view->header_title = '信息中心';
		}
		
		#每个栏目的详细列表
		function indexAction()
		{
			//创建对象
			$Category = new CategoryModel(array('db'=>'db_info'));
			$Entity = new EntityModel(array('db'=>'db_info'));
			
			//获取当前需要显示的分类列表
			$category_id = $this->_getParam('category',0);
			if($category_id == 0) $category_id = $Category->fetchRow()->category_id;
			
			$this->view->category_id = $category_id;
			
			$row_set = $Entity->fetchAll('category_id = '.$category_id);
			
			//按页获取信息列表
			Page::create(array(
			"href_open" => "<a href='/info/?category={$category_id}&p=%d'>", 
			"href_close" => "</a>", 
			"num_rows" => count($row_set),
			"cur_page" => $this->_getParam('p',1)));
			
			$this->view->pagination = Page::$page_str;
			
			//根据分页获取相关信息
			$this->view->rows = $Entity->fetchAll('category_id = '.$category_id, 
												  'entity_pub_time DESC', Page::$pagesize, Page::$offset);
												  
			//分类列表
			$this->view->categories = $Category->fetchAll();
			
			$this->render('front_header', null, true);
			$this->render('index');
			$this->render('front_footer', null, true);
		}
	}