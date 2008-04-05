<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:IndexController.php
 */


/**
 * 你问我答-主控程序
 */
class IndexController extends Zend_Controller_Action
{

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
	private $_sessHelp = null;

    /**
     * 初始化
     * 
     * @return void
     */
    public function init()
    {
		//载入公共SESSION
		$this->_sessCommon = Zend_Registry::get('sessCommon');
		//载入项目SESSION
		$this->_sessHelp   = Zend_Registry::get('sessHelp');

		if (!isset($this->_sessCommon->login)) { $this->_redirect('../member/',array('exit'=>true)); }

		//页面配置
    	$this->view->headScript()->appendFile('/static/scripts/help/help.js');
		$this->view->headLink()->appendStylesheet('/static/styles/help.css','screen');
		$this->view->headTitle('校友互助');
		$this->_helper->layout->setLayout('main');

		//当前模块
		$this->view->header = array('model_name'=>'help');

		$this->view->role = 'member';
		$this->view->account_info = array(
    	    'name'=>$this->_sessCommon->login['realName'],
    	    'letter'=>'2',
    	);
    }

    /**
     * help验证码
     * 
     * @return void
     */
    public function verifyAction()
    {
		//禁用自动渲染视图
		$this->_helper->viewRenderer->setNoRender();

		//将验证码写入公共SESSION
		ImageHandle::verify('common');
    }

    /**
     * 你问我答退出
     * 
     * @return void
     */
	public function logoutAction()
    {
    	Zend_Session::destroy(true);
    	$this->_redirect('../member/');
    }

    /**
     * 你问我答首页
     * 
     * @return void
     */
	public function indexAction()
    {
    }

    /**
     * 问答入口初始
     * 
     * @return void
     */
	public function entryAction()
    {
		//禁用自动渲染视图
		$this->_helper->viewRenderer->setNoRender();
    }
}
