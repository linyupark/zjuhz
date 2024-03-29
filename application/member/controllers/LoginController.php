<?php

/**
 * @category   zjuhz.com
 * @package    member
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:LoginController.php
 */


/**
 * 校友中心-登录控制
 */
class LoginController extends Zend_Controller_Action
{
	/**
     * 公用Session
     * 
     * @var object
     */
	private $_sessCommon = null;

    /**
     * 初始化
     * 
     * @return void
     */
    public function init()
    {
    	$this->_sessCommon = Zend_Registry::get('sessCommon');
    }

    /**
     * 会员登录-通用登录
     * 
     * @return void
     */
    public function indexAction()
    {
    	('member' !== $this->_sessCommon->role ? '' : 
    	    $this->_redirect(($_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : '../'), array('exit')));

    	$this->view->headTitle(Zend_Registry::get('iniMember')->head->titleLogin);
    	$this->view->uname = $_COOKIE['zjuhz_member']['uname']; // 记住账号
        $this->view->pswd = Commons::decrypt($_COOKIE['zjuhz_member']['pswd']); // 记住账号
    	$this->view->redirect = $_SERVER['HTTP_REFERER']; // 上页来源
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
			$postArgs = $this->getRequest()->getPost();
			$postArgs['ip'] = Commons::getIp();

			if ($loginArgs = LoginFilter::init()->login($postArgs))
			{
				if ($result = UserLogic::init()->login($loginArgs))
				{
					// 登录成功
					$this->_sessCommon->role  = 'member';
					$this->_sessCommon->login = $result;
					// only for card cache
					$result['lastLogin'] = REQUEST_TIME;

					// 记住账号
					//((null == $postArgs['alive']) ? setcookie('zjuhz_member[alive]', $result['username'], time() - 2592000, '/') : 
					//    setcookie('zjuhz_member[alive]', $result['username'], time() + 2592000, '/'));
					
					// 记住登录状态
					if($postArgs['alive'] != null)
                    {
                        setcookie('zjuhz_member[uname]', $postArgs['uname'], time() + 3600*24*30, '/');
                        setcookie('zjuhz_member[pswd]', Commons::encrypy($postArgs['pswd']), time() + 3600*24*30, '/');
                    }
                    else
                    {
                        setcookie('zjuhz_member[uname]', null, time() - 3600*24*30, '/');
                        setcookie('zjuhz_member[pswd]', null, time() - 3600*24*30, '/');
                    }

					// 名片缓存
					CacheLogic::setOptions('cache_dir', Commons::getUserCache($result['uid']));
					CacheLogic::init()->cardSave($result);

					echo 'redirect'; // 请求ajax跳转
				}
				else
				{
					// 登录失败
					Zend_Session::destroy(true);
					echo Zend_Registry::get('iniMember')->hint->loginFailure;
				}
			}
		}
	}
}
