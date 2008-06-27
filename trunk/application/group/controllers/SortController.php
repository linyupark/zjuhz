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
		
	}

}
