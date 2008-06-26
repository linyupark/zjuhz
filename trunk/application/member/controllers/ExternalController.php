<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:ExternalController.php
 */


/**
 * 校友中心-外部调用
 */
class ExternalController extends Zend_Controller_Action
{
	/**
     * 初始化
     * 
     * @return void
     */
	public function init()
	{
	}

	/**
     * 外部调用-在线人数 online number
     * 
     * @return void
     */
	public function olnumAction()
	{
		//$this->_helper->viewRenderer->setNoRender();
		//$this->_helper->layout->disableLayout();

		echo CacheLogic::init()->onlineLoad('num');
		print_r(CacheLogic::init()->onlineLoad());
		exit;
	}
}
