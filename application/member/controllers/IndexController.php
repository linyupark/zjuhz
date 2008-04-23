<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:IndexController.php
 */


/**
 * 会员中心-主控程序
 */
class IndexController extends Zend_Controller_Action
{
	/**
     * 项目SESSION对象
     *
     * @var array
     */
	private $_sessMember = null;

    /**
     * 初始化
     * 
     * @return void
     */
    public function init()
    {
		// 载入项目SESSION
		$this->_sessMember = Zend_Registry::get('sessMember');	
    }

    /**
     * member验证码
     * 
     * @return void
     */
    public function verifyAction()
    {
		$this->_helper->viewRenderer->setNoRender(); // 禁用自动渲染视图
		$this->_helper->layout->disableLayout(); // 禁用layout

		// 将验证码写入公共SESSION
		ImageHandle::verify('common');
    }

    /**
     * 信息提示
     * 
     * @return void
     */
	public function messageAction()
    {
    	$this->view->message = $this->_sessMember->message;
    }

    /**
     * 会员中心首页兼登录
     * 
     * @return void
     */
	public function indexAction()
    {
    	// 记住账号
    	//$this->view->uname = Zend_Controller_Request_Http::getCookie('alive');
    	$this->view->uname = $_COOKIE['zjuhz_member']['alive'];
    }
}
