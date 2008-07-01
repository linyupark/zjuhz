<?php

/**
 * 群组分类 SortController
 * 
 * @author zjuhz.com
 * @version 
 */


class SortController extends Zend_Controller_Action
{
	function init()
	{
		$this->view->sort_id = $this->_getParam('id'); // 群组分类记号
	}
	
	public function indexAction()
	{
		$page = $this->_getParam('p',1);
		$orderby = $this->_getParam('o','create_time');
		Page::$pagesize = 20;
		$result = GroupModel::getListBySortId($this->view->sort_id,Page::$pagesize, $page, $orderby);
		Page::create(array(
			'href_open' => '<a href="/group/sort?id='.$this->view->sort_id.'&o='.$orderby.'p=%d">',
			'href_close' => '</a>',
			'num_rows' => $result['numrows'],
			'cur_page' => $page
		));
		$this->view->orderby = $orderby;
		$this->view->groups = $result['rows'];
		$this->view->pagination = Page::$page_str;
		$this->view->group_num = $result['numrows'];
	}

}
