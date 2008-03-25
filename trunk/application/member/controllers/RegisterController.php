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
		//载入项目配置
		$this->_iniMember  = Zend_Registry::get('iniMember');
		//载入公共SESSION
		$this->_sessCommon = Zend_Registry::get('sessCommon');
		//载入项目SESSION
		$this->_sessMember = Zend_Registry::get('sessMember');	
    }

    /**
     * 会员注册-显示页面
     * 
     * @return void
     */
	public function indexAction()
    {
    	$this->_forward('register','Index');
    }

    /**
     * 会员注册-显示页面
     * 
     * @return void
     */
	public function registerAction()
    {
    	$this->_forward('register','Index');
    }

	/**
     * 会员注册-数据提交
     * 
     * @return string to ajax
     */
	public function doregisterAction()
	{
		//禁用自动渲染视图
		$this->_helper->viewRenderer->setNoRender();

		if ($this->getRequest()->isXmlHttpRequest())
		{
			//此处接收传递的数据数组
			$input = $this->getRequest()->getPost(); //print_r($input);exit;
			//此处单独处理的数据单独取出
			$vcode = $input['vcode'];
			$scode = $this->_sessCommon->verify;
			//此处可注入数据将用与判断

			//此处注销无用数据
			unset($this->_sessCommon->verify);

			$filter = RegisterFilter::init();
			if ($input = $filter->register($input))
			{
				if (Commons::checkVerify($vcode,$scode))
				{
					$logic = RegisterLogic::init();	
				
					$this->_sessMember->message = (($logic->register($input)) ? 
					    $this->_iniMember->hint->register->success : 
					        $this->_iniMember->hint->register->failure);

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
		//禁用自动渲染视图
		$this->_helper->viewRenderer->setNoRender();

		if ($this->getRequest()->isXmlHttpRequest())
		{
			//此处接收传递的数据数组
			$input = $this->getRequest()->getPost(); //print_r($posts);exit;
			//此处单独处理的数据单独取出

			//此处可注入数据将用与判断

			//此处注销无用数据			

			
			$filter = RegisterFilter::init();
			if ($userName = $filter->check($input))
			{
				$logic = RegisterLogic::init();

				echo (($logic->check($userName)) ? 
				    $this->_iniMember->hint->userName->isExist : 
				        $this->_iniMember->hint->userName->notExist);
			}
		}
	}
}
