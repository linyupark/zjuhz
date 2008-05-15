<?php

	/**
	 * 班级成员进行邀请操作
	 *
	 */

	class InviteController extends Zend_Controller_Action 
	{
		function init()
		{
			$this->_sessClass = Zend_Registry::get('sessClass');
			
			$this->view->class_id = $this->getRequest()->getParam('c');
			$this->view->class_base_info = DbModel::getClassInfo($this->view->class_id);
		}
		
		function indexAction()
		{
			$this->view->headTitle('邀请校友加入');
		}
	}

?>