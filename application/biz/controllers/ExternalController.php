<?php

/**
 * @category   zjuhz.com
 * @package    biz
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:ExternalController.php
 */


/**
 * 校友企业-外部调用
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
     * 外部调用-基础数据
     * 
     * @return void
     */
	public function baseAction()
	{
		//$this->_helper->viewRenderer->setNoRender();
		//$this->_helper->layout->disableLayout();

        $type = $this->getRequest()->getParam('type');
		$base = CacheLogic::init()->baseLoad();
		echo $base[$type];
	}
}
