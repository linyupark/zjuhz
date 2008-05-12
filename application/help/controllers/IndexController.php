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
	private $_sessHelp = null;

	/**
     * 问答模块配置
     *
     * @var object
     */
	private $_iniHelp = null;

	/**
     * 初始化
     *
     * @return void
     */
	public function init()
	{
		$this->_sessCommon = Zend_Registry::get('sessCommon');
		$this->_sessHelp   = Zend_Registry::get('sessHelp');
		$this->_iniHelp    = Zend_Registry::get('iniHelp');

		$this->view->sessCommon = $this->_sessCommon;
		$this->view->sessHelp   = $this->_sessHelp;
	}

	/**
     * help验证码
     * 
     * @return void
     */
	public function verifyAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();

		ImageHandle::verify('common');
	}

    /**
     * 弹窗信息提示
     * 
     * @return void
     */
    public function messageAction()
    {
		$this->_helper->layout->disableLayout();

		$this->view->message = $this->_sessHelp->message;
    }

	/**
     * 首次使用时先激活
     * 
     * @return void
     */
	public function activateAction()
	{
		// 判断是否是已登录会员且首次使用
		if ('member' === $this->_sessCommon->role && 'N' === $this->_sessCommon->login['initAsk'])
		{
			$loginArgs = $this->_sessCommon->login;
			$loginArgs['point'] = $this->_iniHelp->point->init; // 积分初始值

			if ($actArgs = IndexFilter::init()->activate($loginArgs))
			{
				if (AskLogic::init()->insert($actArgs))
				{
               		$this->_sessCommon->login['initAsk'] = 'Y';
               		HelpClient::init()->UserExtUpdate($actArgs['uid']); // 通知更新

					// 写入积分日志
					PointLogLogic::init()->insert(array(
					    'uid' => $actArgs['uid'], 'point' => $actArgs['point'], 'type' => 1, 
					));
				}
			}
		}
	}

	/**
     * 模块使用时先登录
     * 
     * @return void
     */
	public function entryAction()
	{
		if ('member' === $this->_sessCommon->role && !$this->_sessHelp->login)
		{
			$this->_sessHelp->login = AskLogic::init()->selectRow($this->_sessCommon->login['uid']);
		}
	}

	/**
     * 你问我答首页
     * 
     * @return void
     */
	public function indexAction()
	{
		$this->view->headTitle($this->_iniHelp->head->titleIndex);
		$this->view->headScript()->appendFile('/static/scripts/help/index/index.js');

		$this->activateAction(); // 首次使用激活
		$this->entryAction(); // 子系统登录

		$logic = AskQuestionLogic::init();
		$this->view->rand   = AskQuestionLogic::init()->selectRandQuestion(5); // 随机
		$this->view->latest = $logic->selectAllLatest(12); // 最新问题
		$this->view->offer  = $logic->selectAllOffer(12);  // 高分问题
		$this->view->forget = $logic->selectAllForget(12); // 被遗忘的
		$this->view->solved = $logic->selectAllSolved(12); // 最近解决	
	}
}
