<?php

/**
 * 群组的创建 CreateController
 * 
 * @author zjuhz.com
 * @version 
 */

class CreateController extends Zend_Controller_Action {
	
	/**
	 * 创建的表单显示
	 */
	public function indexAction() 
	{
		$this->view->headTitle('创建新群组');
	}
	
	/**
	 * 对提交的表单数据进行操作
	 *
	 */
	public function doAction()
	{
		
	}

}
