<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:RegisterController.php
 */


/**
 * 会员中心-注册流程
 */
class RegisterController extends Zend_Controller_Action
{
	/**
     * 公用Session
     *
     * @var object
     */
	private $_sessCommon = null;

	/**
     * 项目Session
     *
     * @var object
     */
	private $_sessMember = null;

	/**
     * 项目模块配置
     *
     * @var object
     */
	private $_iniMember = null;

    /**
     * 初始化
     * 
     * @return void
     */
    public function init()
    {
		$this->_sessCommon = Zend_Registry::get('sessCommon');
		$this->_sessMember = Zend_Registry::get('sessMember');
		$this->_iniMember  = Zend_Registry::get('iniMember');

		$this->view->sessCommon = $this->_sessCommon;
    }

    /**
     * 会员注册-显示页面
     * 
     * @return void
     */
	public function indexAction()
    {
    	$this->view->headTitle($this->_iniMember->head->titleRegister);
    	$this->view->headScript()->appendFile('/static/scripts/member/register/index.js');

    	$this->view->ikey = $this->getRequest()->getParam('ikey'); // 获取邀请码
    }

    /**
     * 会员注册-邀请注册
     * 
     * @return void
     */
	public function inviteAction()
    {
    	$this->_forward('index');
    }

    /**
     * 会员注册-注册完成
     * 
     * @return void
     */
	public function welcomeAction()
    {
		$this->view->headTitle($this->_iniMember->head->titleWelcome);

    	$this->view->register = $this->_sessMember->register;
    }

	/**
     * 会员注册-数据提交
     *
     * @return void
     */
	public function doregisterAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();

		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['ip'] = Commons::getIp();

			if ($regArgs = RegisterFilter::init()->register($postArgs))
			{
				if (MemberClass::checkVerifyCode($postArgs['vcode'], $this->_sessCommon->verify))
				{
					$this->_sessMember->register = ((UserLogic::init()->register($regArgs)) ? $regArgs : '');

				    echo 'redirect'; // 请求ajax跳转
				}
			}
		}
	}

	/**
     * 会员注册-账号检测
     * 
     * @return void
     */
	public function docheckAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();

		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();			

			if ($username = RegisterFilter::init()->check($postArgs))
			{
				echo ((UserLogic::init()->selectUsernameCount($username)) ? $this->_iniMember->hint->usernameIsExist : 
				    $this->_iniMember->hint->usernameNotExist
				);
			}
		}
	}
}
