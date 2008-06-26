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
     * 包括游客及已登录会员的所有在线人员
     * 
     * @return void
     */
	public function olnumAction()
	{
		//$this->_helper->viewRenderer->setNoRender();
		//$this->_helper->layout->disableLayout();

		//print_r(CacheLogic::init()->onlineLoad());
		echo CacheLogic::init()->onlineLoad('num');
		exit;
	}

	/**
     * 外部调用-在线详情(json) online detail
     * 只显示已登录会员不包括游客
     * 
     * @return void
     */
	public function oljsonAction()
	{
		//$this->_helper->viewRenderer->setNoRender();
		//$this->_helper->layout->disableLayout();

		echo Zend_Json::encode(CacheLogic::init()->onlineLoad());
		exit;
	}
}
