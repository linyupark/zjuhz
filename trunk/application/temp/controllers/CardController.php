<?php


class CardController extends Zend_Controller_Action {
	
	/**
	 * 提前处理
	 *
	 */
	public function preDispatch()
	{
		
	}
	
	public function indexAction() {
		
	}
	
	public function loadAction()
	{
		$this->_helper->layout->disableLayout();
		echo $this->getRequest()->getParam('id');
	}

}
