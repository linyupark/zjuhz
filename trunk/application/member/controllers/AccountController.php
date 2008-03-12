<?php

/**
 * [member] (C)2008 zjuhz.com
 * 
 * $File : AccountController.php $
 * $Author : wangyumin $
 */


/**
 * 会员中心-帐号处理
 * 面向前后端:帐号创建 资料修改 修改修改等
 * 纯数据处理，不做页面层显示
 */
class AccountController extends Zend_Controller_Action
{
	/**
     * 配置文档对象
     *
     * @var object
     */
	private $_ini = null;

	/**
     * 公共SESSION对象
     *
     * @var array
     */
	private $_sessCommon = null;

	/**
     * 初始化
     * 
     * @return null
     */
	public function init()
	{
		//载入配置文档
		$this->_ini        = Zend_Registry::get('ini');
		
		//载入公共SESSINO
		$this->_sessCommon = Zend_Registry::get('sessCommon');
		
		//载入对应MODEL类
		Zend_Loader::loadClass('UserModel');

		//禁用自动渲染视图
		$this->_helper->viewRenderer->setNoRender();

		return null;
	}

	/**
     * 注册提交
     * 
     * 点击提交按钮后数据验证及写库操作
     * 
     * @return string to ajax
     */
	public function registerAction()
	{
		if ($this->_request->isXmlHttpRequest())
		{
			//print_r($this->_getAllParams());
			$username = $this->_request->getPost('username');
			$passwd   = $this->_request->getPost('passwd');
			$repasswd = $this->_request->getPost('repasswd');
			$realname = $this->_request->getPost('realname');
			$sex      = $this->_request->getPost('sex','S');
			$email    = $this->_request->getPost('email');

			if (!Valid::chkVerify($this->_sessCommon->verify,$this->_request->getPost('verifycode')))
			{
				echo $this->_ini->hint->verifycode->checkError;
			}
			elseif (!Valid::chkUsername($username))
			{
				echo $this->_ini->hint->username->formatError;
			}
			elseif (!Valid::chkPasswd($passwd))
			{
				echo $this->_ini->hint->passwd->formatError;
			}
			elseif (!Valid::equal($passwd,$repasswd))
			{
				echo $this->_ini->hint->passwd->notEqual;
			}
			elseif (!Valid::chkRealname($realname))
			{
				echo $this->_ini->hint->realname->formatError;
			}
			elseif (!Valid::chkEmail($email))
			{
				echo $this->_ini->hint->email->formatError;
			}
			else
			{
				$user = new UserModel();
				$user->register(Filter::request($this->_getAllParams()));
				
				//Filter::request($this->_getAllParams())
			}
		}
	}
	
}
