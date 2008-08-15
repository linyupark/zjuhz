<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:RegisterController.php
 */


/**
 * 校友中心-注册流程
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
     * 公用模块配置
     * 
     * @var object
     */
	private $_iniCommon = null;

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
		$this->_iniCommon  = Zend_Registry::get('iniCommon');
		$this->_iniMember  = Zend_Registry::get('iniMember');

		('member' == $this->_sessCommon->role ? $this->_redirect('../', array('exit')) : '');
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
    	$this->view->headLink()->appendStylesheet('/static/styles/passwdcheck.css','screen');

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
		$this->view->headScript()->appendFile('/static/scripts/member/register/index.js');

    	$this->view->register = $this->_sessMember->register;
    }

	/**
     * 会员注册-数据提交
     * 
     * @return void
     */
	public function doregisterAction()
	{
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['ip']    = Commons::getIp();
			$postArgs['scode'] = $this->_sessCommon->verify;

			if ($regArgs = RegisterFilter::init()->register($postArgs))
			{
				$this->_sessMember->register = null;
				if ($uid = UserLogic::init()->register($regArgs))
				{
					$this->_sessMember->register            = $regArgs;
					$this->_sessMember->register['uid']     = $uid;
				    $this->_sessMember->register['classes'] = $postArgs['classes'];
				}

				echo 'redirect'; // 请求ajax跳转
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
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();			

			if ($username = RegisterFilter::init()->check($postArgs))
			{
				echo (UserLogic::init()->selectUsernameCount($username) ? 
				    $this->_iniMember->hint->usernameIsExist : 
				    $this->_iniMember->hint->usernameNotExist);
			}
		}
	}

	/**
     * 会员注册-显示班级
     * 
     * @return void
     */
	public function doclassesAction()
	{
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$cid = $this->getRequest()->getParam('cid');

			if (RegisterFilter::init()->classes($cid))
			{
				if ($cardArgs = AddressCardLogic::init()->selectCidToUid($cid))
				{
					CacheLogic::setOptions('cache_dir', Commons::getUserCache($cardArgs['uid']));
    	            echo Zend_Json::encode(CacheLogic::init()->classLoad());
				}
			}
		}
	}
}
