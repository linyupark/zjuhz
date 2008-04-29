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
     * 会员模块配置对象
     *
     * @var object
     */
	private $_iniMember = null;

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
		$this->_iniMember  = Zend_Registry::get('iniMember'); // 载入项目配置
		$this->_sessMember = Zend_Registry::get('sessMember'); // 载入项目SESSION
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

		ImageHandle::verify('common');
    }

    /**
     * 弹窗信息提示
     * 
     * @return void
     */
	public function messageAction()
    {
    	$this->_helper->layout->disableLayout(); // 禁用layout

    	$this->view->message = $this->_sessMember->message;
    }

    /**
     * 注册信息提示
     * 
     * @return void
     */
	public function welcomeAction()
    {
		$this->view->headTitle($this->_iniMember->head->title->welcome); // 载入标题

    	$this->view->message = $this->_sessMember->message;
    }

    /**
     * 会员中心首页
     * 
     * @return void
     */
	public function indexAction()
    {
    	$this->_redirect('/my/');
    }
}
