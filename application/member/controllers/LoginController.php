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
		$this->_iniMember  = Zend_Registry::get('iniMember'); // 载入项目配置
		$this->_sessCommon = Zend_Registry::get('sessCommon'); // 载入公共SESSION

		$this->_helper->viewRenderer->setNoRender(); // 禁用自动渲染视图
		$this->_helper->layout->disableLayout(); // 禁用layout
    }

    /**
     * 登录
     * 
     * @return void
     */
    public function indexAction()
    {
		$this->_redirect('/my/');
    }

	/**
     * 会员登录-账密检测
     * 
     * @return void
     */
	public function dologinAction()
	{
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs       = $this->getRequest()->getPost();
			$postArgs['ip'] = Commons::getIp();

			if ($loginArgs = LoginFilter::init()->login($postArgs))
			{
				if ($result = UserLogic::init()->login($loginArgs))
				{
					// 登录成功
					$this->_sessCommon->role  = 'member';
					$this->_sessCommon->login = $result;

					// 记住账号
					((null === $postArgs['alive']) ?  
					    setcookie('zjuhz_member[alive]', $result['username'], time() - 2592000, '/') : 
					    setcookie('zjuhz_member[alive]', $result['username'], time() + 2592000, '/')
					);

					echo 'redirect'; // 请求ajax跳转
				}
				else
				{
					// 登录失败
					Zend_Session::destroy(true);

					echo $this->_iniMember->hint->loginFailure;
				}
			}
		}
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
}
