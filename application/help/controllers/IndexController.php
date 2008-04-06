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
     * 问答模块配置对象
     *
     * @var object
     */
	private $_iniHelp = null;

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
		//判断是否已登录
		(!isset(Zend_Registry::get('sessCommon')->login) ? self::_redirect('../member/',array('exit'=>true)) : '');

		//载入项目配置
		$this->_iniHelp    = Zend_Registry::get('iniHelp');
		//载入公共SESSION
		$this->_sessCommon = Zend_Registry::get('sessCommon');
		//载入项目SESSION
		$this->_sessHelp   = Zend_Registry::get('sessHelp');

		//当前模块
		$this->view->header = array('model_name'=>'help');
		//载入JS文件
    	$this->view->headScript()->appendFile('/static/scripts/help/help.js');
    	//载入CSS文件
		$this->view->headLink()->appendStylesheet('/static/styles/help.css','screen');
		//载入标题
		$this->view->headTitle($this->_iniHelp->head->title);
		//载入主模板
		$this->_helper->layout->setLayout('main');

		$this->view->role = 'member';
		$this->view->account_info = array(
    	    'userName'=>$this->_sessCommon->login['realName'],
    	    'letter'=>'0',
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
     * 你问我答首页
     * 
     * @return void
     */
	public function indexAction()
    {
    	
    }
}
