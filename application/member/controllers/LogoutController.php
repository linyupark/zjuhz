<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:LogoutController.php
 */


/**
 * 会员中心-退出登录
 */
class LogoutController extends Zend_Controller_Action
{
    /**
     * 初始化
     * 
     * @return void
     */
    public function init()
    {
		$this->_helper->viewRenderer->setNoRender(); // 禁用自动渲染视图
		$this->_helper->layout->disableLayout(); // 禁用layout
    }

    /**
     * 清空SESSION并重定向
     * 
     * @return void
     */
	public function indexAction()
    {
    	Zend_Session::destroy(true);
    	$this->_redirect('../member/');
    }
}
