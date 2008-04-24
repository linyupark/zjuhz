<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:RegisterController.php
 */


/**
 * 会员中心-注册流程控制
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
		// 载入项目配置
		$this->_iniMember  = Zend_Registry::get('iniMember');
		// 载入公共SESSION
		$this->_sessCommon = Zend_Registry::get('sessCommon');
		// 载入项目SESSION
		$this->_sessMember = Zend_Registry::get('sessMember');
    }

    /**
     * 会员注册-显示页面
     * 
     * @return void
     */
	public function indexAction()
    {
    	// 获取注册邀请码
    	$this->view->ikey = $this->getRequest()->getParam('ikey');
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
			// 此处接收传递的数据数组
			$postArgs = $this->getRequest()->getPost();
			// 此处单独处理的数据单独取出 // next, see standard
			$vCode    = $postArgs['vcode'];
			$sCode    = $this->_sessCommon->verify;
			// 此处注销无用数据
			unset($this->_sessCommon->verify);

			if ($regArgs = RegisterFilter::init()->register($postArgs))
			{
				if (MemberClass::checkVerifyCode($vCode, $sCode))
				{
					$this->_sessMember->message = ((RegisterLogic::init()->register($regArgs)) ? 
				        $this->_iniMember->hint->register->success : 
				            $this->_iniMember->hint->register->failure);

				    echo 'message'; // 请求ajax给出提示
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
			// 此处接收传递的数据数组 // next, see standard
			$postArgs = $this->getRequest()->getPost();			

			if ($username = RegisterFilter::init()->check($postArgs))
			{
				echo ((RegisterLogic::init()->check($username)) ? 
				    $this->_iniMember->hint->username->isExist : 
				        $this->_iniMember->hint->username->notExist);
			}
		}
	}
}
