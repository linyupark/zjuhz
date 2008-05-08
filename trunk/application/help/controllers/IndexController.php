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
		$this->_iniHelp    = Zend_Registry::get('iniHelp'); // 载入项目配置
		$this->_sessCommon = Zend_Registry::get('sessCommon'); // 载入公共SESSION
		$this->_sessHelp   = Zend_Registry::get('sessHelp'); // 载入项目SESSION

		$this->_sessUid    = $this->_sessCommon->login['uid']; // sessionUid

		$this->view->sessCommon = $this->_sessCommon; // Session资料注入
		$this->view->sessHelp   = $this->_sessHelp; // Session资料注入
	}

	/**
     * help验证码
     * 
     * @return void
     */
	public function verifyAction()
	{
		$this->_helper->viewRenderer->setNoRender(); // 禁用自动渲染视图
		$this->_helper->layout->disableLayout(); // 禁用layout

		ImageHandle::verify('common'); // 将验证码写入公共SESSION
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
     * Session消息提示页
     * 
     * @return void
     */
    public function messageAction()
    {
		$this->_helper->layout->disableLayout(); // 禁用layout

		$this->view->message = $this->_sessHelp->message;
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

			if ($actArgs = AskFilter::init()->activate($loginArgs))
			{
				if (AskLogic::init()->activate($actArgs))
				{
               		$this->_sessCommon->login['initAsk'] = 'Y';
               		HelpClient::init()->activate($actArgs['uid']); // 通知更新

					// 积分日志 数据已过滤可直接生成
					LogLogic::init()->insert(array(
					    'uid' => $actArgs['uid'], 'point' => $actArgs['point'], 'type' => 1, 
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
			if ($result = AskLogic::init()->entry($this->_sessUid))
			{
				$this->_sessHelp->login = $result;
			}
		}
	}

	/**
     * 你问我答首页
     * 
     * @return void
     */
	public function indexAction()
	{
		$this->view->headTitle($this->_iniHelp->head->title->index->index); // 载入标题
		$this->view->headScript()->appendFile('/static/scripts/help/index/index.js'); // 载入JS脚本

		$this->activateAction(); // 首次使用激活
		$this->entryAction(); // 子系统登录

		$logic = HelpLogic::init();
		$this->view->latest = $logic->latest(12); // 最新问题
		$this->view->offer  = $logic->offer(12); // 高分问题
		$this->view->forget = $logic->forget(12); // 被遗忘的
		$this->view->solved = $logic->solved(12); // 最近解决
		$this->view->rand   = QuestionLogic::init()->rand(5); // 随机
	}
}
