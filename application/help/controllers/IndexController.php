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
     * sess内的会员编号
     *
     * @var integer
     */
	private $_sessUid = 0;

	/**
     * 初始化
     *
     * @return void
     */
	public function init()
	{
		// 载入项目配置
		$this->_iniHelp    = Zend_Registry::get('iniHelp');
		// 载入公共SESSION
		$this->_sessCommon = Zend_Registry::get('sessCommon');
		// 载入项目SESSION
		$this->_sessHelp   = Zend_Registry::get('sessHelp');
		// sessionUid
		$this->_sessUid    = $this->_sessCommon->login['uid'];		

		// 登录资料注入
		$this->view->login = $this->_sessCommon->login;
	}

	/**
     * help验证码
     * 
     * @return void
     */
	public function verifyAction()
	{
		// 禁用自动渲染视图
		$this->_helper->viewRenderer->setNoRender();
		// 禁用layout
		$this->_helper->layout->disableLayout();

		// 将验证码写入公共SESSION
		ImageHandle::verify('common');
	}

	/**
     * 返回登录入口
     * 
     * @return void
     */
	public function loginAction()
	{
		$this->_redirect('../member/');
	}

	/**
     * 注册会员使用时需先激活
     * 
     * @return void
     */
	public function activateAction()
	{
		// 注册会员使用时需先激活
		if ('member' === $this->_sessCommon->role && 'N' === $this->_sessCommon->login['initAsk'])
		{
			// 此处接收传递的数据数组 // next, see standard
			$loginArgs = $this->_sessCommon->login;
			// 此处可注入数据将用与判断 // next, see standard
			$loginArgs['point'] = $this->_iniHelp->point->init; // 积分初始值

			if ($actArgs = IndexFilter::init()->activate($loginArgs))
			{
				if (IndexLogic::init()->activate($actArgs))
				{
               		$this->_sessCommon->login['initAsk'] = 'Y';
               		HelpClient::init()->activate($this->_sessUid); // 通知更新

					// 数据已过滤可直接生成
					LogLogic::init()->insert(array(
					    'uid' => $this->_sessUid, 'point' => $loginArgs['point'], 'after' => $loginArgs['point'], 'type' => 1, 
					));
				}
			}
		}
	}

	/**
     * 注册会员给予子系统SESS
     * 
     * @return void
     */
	public function entryAction()
	{
		// 注册会员给予子系统SESS
		if ('member' === $this->_sessCommon->role && !$this->_sessHelp->login)
		{
			if ($result = IndexLogic::init()->entry($this->_sessUid))
			{
				$this->_sessHelp->login = $result; //exit('module entry!');
			}
		}
	}

    /**
     * 测试
     * 
     * @return void
     */
    public function testAction()
    {
		// 禁用自动渲染视图
		$this->_helper->viewRenderer->setNoRender();
		// 禁用layout
		$this->_helper->layout->disableLayout();

        print_r($_SESSION);
        exit;
    }

    /**
     * 模块统一消息提示页
     * 
     * @return void
     */
    public function messageAction()
    {
		$this->view->message = $this->_sessHelp->message;
    }

	/**
     * 你问我答首页
     * 
     * @return void
     */
	public function indexAction()
	{
		// 载入标题
		$this->view->headTitle($this->_iniHelp->head->title->index->index);
		// 载入JS脚本
		$this->view->headScript()->appendFile('/static/scripts/help/index/index.js');

		// 首次使用激活
		$this->activateAction();
		// 子系统登录
		$this->entryAction();
	}
}