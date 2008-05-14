<?php

/**
 * 专题控制器（静态页）
 * 
 * @author Linyu
 */


class SubjectController extends Zend_Controller_Action {
	
	function init()
	{
		$this->view->headLink()->appendStylesheet('/static/styles/home.css','screen')
							   ->appendStylesheet('/static/styles/info_front.css','screen');
		$this->view->headScript()->appendFile('/static/scripts/thickbox-compressed.js')
								 ->appendFile('/static/scripts/info/action.js');
		// 分配角色详细信息
		$this->view->login = Zend_Registry::get('sessCommon')->login;
	}
	
	# 通用显示页
	function indexAction()
	{
		$name = $this->getRequest()->getParam('of',FALSE);
		$this->render($name);
	}
	
	# AJAX图片展示
	function albumAction()
	{
		$this->_helper->layout->disableLayout();
		
		$request = $this->_request;
		if($request->isXmlHttpRequest())
		{
			$pagesize = 12; // 分页大小
			$name = $request->getParam('of',FALSE);
			$page = $request->getParam('p',1);
			
			// 获取对应照片缩略图
			$d = dir('../../public/static/subjects/'.$name.'/pics/sample');
			$samples = array();
			while (FALSE !== ($entry = $d->read()))
			{
				if(strstr($entry,'.jpg'))
				$samples[] = $entry; //对相片记录进行存储
			}
			// 分页处理
			Page::$pagesize = $pagesize;
			$page_str = Page::create(array(
				'href_open' => '<a href="javascript:album(\''.$name.'\',%d)">',
				'href_close' => '</a>',
				'num_rows' => count($samples),
				'cur_page' => $page
			));
			$offset = ($page-1)*$pagesize;
			$samples = array_slice($samples, $offset, $pagesize);
			
			$this->view->pages = Page::$num_pages;
			$this->view->name = $name;
			$this->view->pagesize = $pagesize;
			$this->view->page = $page;
			$this->view->pagination = $page_str;
			$this->view->samples = $samples;
		}
	}
}
