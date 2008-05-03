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
     * 初始化
     * 
     * @return void
     */
    public function init()
    {
    }

    /**
     * 验证码
     * 
     * @return void
     */
    public function verifyAction()
    {
		$this->_helper->viewRenderer->setNoRender(); // 禁用自动渲染视图
		$this->_helper->layout->disableLayout(); // 禁用layout

		// $_SESSION['common']['verify']
		ImageHandle::verify();
    }

    /**
     * 弹窗信息提示
     * 
     * @return void
     */
	public function messageAction()
    {
    	$this->_helper->layout->disableLayout();

    	$this->view->message = Zend_Registry::get('sessMember')->message;
    }

    /**
     * 会员中心首页
     * 
     * @return void
     */
	public function indexAction()
    {
    	// 暂时指向我的账号
    	$this->_redirect('/my/');
    }
}
