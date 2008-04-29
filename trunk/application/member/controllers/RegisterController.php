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
     * 项目模块配置对象
     *
     * @var object
     */
	private $_iniMember = null;

	/**
     * 公用SESSION对象
     *
     * @var array
     */
	private $_sessCommon = null;

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
		$this->_sessCommon = Zend_Registry::get('sessCommon'); // 载入公共SESSION
		$this->_sessMember = Zend_Registry::get('sessMember'); // 载入项目SESSION
    }

    /**
     * 会员注册-显示页面
     * 
     * @return void
     */
	public function indexAction()
    {
    	$this->view->headTitle($this->_iniMember->head->title->register); // 载入标题
    	$this->view->headScript()->appendFile('/static/scripts/member/register/index.js'); // 载入JS脚本
    	
    	$this->view->ikey = $this->getRequest()->getParam('ikey'); // 获取注册邀请码
    }

    /**
     * 会员注册-显示页面
     * 
     * @return void
     */
	public function registerAction()
    {
		$this->_forward('index');
    }

	/**
     * 会员注册-数据提交
     *
     * @return string to ajax
     */
	public function doregisterAction()
	{
		$this->_helper->viewRenderer->setNoRender(); // 禁用自动渲染视图
		$this->_helper->layout->disableLayout(); // 禁用layout

		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs       = $this->getRequest()->getPost();
			$postArgs['ip'] = Commons::getIp();
			$sessCode       = $this->_sessCommon->verify;			
			unset($this->_sessCommon->verify);

			if ($regArgs = RegisterFilter::init()->register($postArgs))
			{
				if (MemberClass::checkVerifyCode($postArgs['vcode'], $sessCode))
				{
					$this->_sessMember->message = ((UserLogic::init()->register($regArgs)) ? 
				        $this->_iniMember->hint->registerSuccess : 
				        $this->_iniMember->hint->registerFailure
				    );

				    echo 'redirect'; // 请求ajax跳转
				}
			}
		}
	}

	/**
     * 会员注册-账号检测
     * 
     * @return string to ajax
     */
	public function docheckAction()
	{
		$this->_helper->viewRenderer->setNoRender(); // 禁用自动渲染视图
		$this->_helper->layout->disableLayout(); // 禁用layout

		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();			

			if ($username = RegisterFilter::init()->check($postArgs))
			{
				echo ((UserLogic::init()->check($username)) ? 
				    $this->_iniMember->hint->usernameIsExist : 
				    $this->_iniMember->hint->usernameNotExist
				);
			}
		}
	}
}
