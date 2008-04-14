<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:LoginController.php
 */


/**
 * 会员中心-登录流程控制
 */
class LoginController extends Zend_Controller_Action
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
    }

	/**
     * 会员登录-显示页面
     * 
     * @return void
     */
	public function indexAction()
	{
		$this->_forward('index', 'Index');
	}

	/**
     * 会员登录-显示页面
     * 
     * @return void
     */
	public function loginAction()
	{
		$this->_forward('index', 'Index');
	}

    /**
     * 会员登录-退出登录
     * 
     * @return void
     */
	public function logoutAction()
    {
    	$this->_forward('index', 'Logout');
    }

    /**
     * 会员登录-退出登录
     * 
     * @return void
     */
	public function dologoutAction()
    {
    	$this->_forward('index', 'Logout');
    }

	/**
     * 会员登录-账密检测
     * 
     * @return void
     */
	public function dologinAction()
	{
		// 禁用自动渲染视图
		$this->_helper->viewRenderer->setNoRender();

		if ($this->getRequest()->isXmlHttpRequest())
		{
			// 此处接收传递的数据数组
			$input = $this->getRequest()->getPost();
			// next, see standard

			if ($input = LoginFilter::init()->login($input))
			{
				if ($login = LoginLogic::init()->login($input))
				{
					//登录成功
					$this->_sessCommon->role  = 'member';
					$this->_sessCommon->login = $login;
					//记住账号
					((null === $input['alive']) ? 
					    setcookie('zjuhz_member[alive]', $input['userName'], time() - 2592000, '/') : 
					        setcookie('zjuhz_member[alive]', $input['userName'], time() + 2592000, '/'));

					//成功跳转
					echo 'redirect';
				}
				else
				{
					//登录失败
					Zend_Session::destroy(true);
					echo $this->_iniMember->hint->login->failure;
				}
			}
		}
	}
}
