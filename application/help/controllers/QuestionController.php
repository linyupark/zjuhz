<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:QuestionController.php
 */


/**
 * 校友互助-问题帖子
 */
class QuestionController extends Zend_Controller_Action
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
     * 项目模块配置
     *
     * @var object
     */
	private $_iniHelp = null;

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
		$this->_sessCommon = Zend_Registry::get('sessCommon');
		$this->_sessHelp   = Zend_Registry::get('sessHelp');
		$this->_iniHelp    = Zend_Registry::get('iniHelp');

		$this->_sessUid = $this->_sessCommon->login['uid'];

		$this->view->sessCommon = $this->_sessCommon;
		$this->view->sessHelp   = $this->_sessHelp;

		// 若未激活则转至首页
		(('member' === $this->_sessCommon->role && 'N' === $this->_sessCommon->login['initAsk']) ? 
		    $this->_redirect('/', array('exit')) : '');
		
		// 子系统登录
		$this->_sessHelp->login = ('member' === $this->_sessCommon->role && !$this->_sessHelp->login ? 
		    AskLogic::init()->selectUidRow($this->_sessCommon->login['uid']) : $this->_sessHelp->login);
	}

	/**
     * 问题首页
     * 
     * @return void
     */
	public function indexAction()
	{
	}

	/**
     * 发布问题-显示页面
     * 
     * @return void
     */
	public function insertAction()
	{		
		$this->view->headTitle($this->_iniHelp->head->titleQuestionInsert);
		$this->view->headScript()->appendFile('/static/scripts/help/question/insert.js');

		$this->view->title = $this->getRequest()->getParam('title');
	}

	/**
     * 发布问题-数据提交
     * 
     * @return void
     */
	public function doinsertAction()
	{
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['point'] = $this->_sessHelp->login['point'];
			$postArgs['uid']   = $this->_sessUid;
			$postArgs['scode'] = $this->_sessCommon->verify;

			$sorter = array('sort0' => (int)$postArgs['sort0'], 'sort1' => (int)$postArgs['sort1'],  
			    'sort2' => (int)$postArgs['sort2'], 'filed' => 'question');

			if ($insArgs = QuestionFilter::init()->insert($postArgs))
			{
				if ($qid = AskQuestionLogic::init()->insert($insArgs))
				{
					// sess内的各值对应变化
					$this->_sessHelp->login['point'] = $postArgs['point'] - $insArgs['offer']; // 可用积分
					$this->_sessHelp->login['question']++; // 总问题数
					$this->_sessHelp->login['unsolved']++; // 未解决数

					// 分类数量更新
					AskSortLogic::init()->counter($sorter);

					// 积分日志
					(0 < $insArgs['offer'] ? PointLogLogic::init()->insert(array(
					    'uid' => $insArgs['uid'], 'qid' => $qid, 'point' => "-{$insArgs['offer']}", 'type' => 2)) : '');

					// 写入信息提示
                    $this->_sessHelp->message = $this->_iniHelp->hint->questionSuccess;

					echo 'message'; // 请求ajax给出提示
				}
			}
		}
	}

	/**
     * 发布问题-采纳答案
     * 
     * @return void
     */
	public function doacceptAction()
	{
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['quid']  = $this->_sessUid;
			$postArgs['scode'] = $this->_sessCommon->verify;

			$sorter  = array('sort0' => (int)$postArgs['sort0'], 'sort1' => (int)$postArgs['sort1'], 
			    'sort2' => (int)$postArgs['sort2'], 'filed' => 'solved');

			if ($insArgs = QuestionFilter::init()->accept($postArgs))
			{
				if ((0 <= ($offer = AskQuestionLogic::init()->accept($insArgs))))
				{
					// sessHelp内的各值对应变化
					$this->_sessHelp->login['unsolved']--;
					$this->_sessHelp->login['solved']++;

					// 分类数量更新
					AskSortLogic::init()->counter($sorter);

					// 积分日志
					(0 < $offer ? PointLogLogic::init()->insert(array(
					    'uid' => $insArgs['ruid'], 'qid' => $insArgs['qid'], 'point' => $offer, 'type' => 4)) : '');

					// 写入信息提示
					$this->_sessHelp->message = $this->_iniHelp->hint->replyAccept;

					echo 'message'; // 请求ajax给出提示
				}
			}
		}
	}

	/**
     * 显示问题-显示页面
     * 
     * @return void
     */
	public function detailAction()
	{
		$qid      = $this->getRequest()->getParam('qid');
		$question = AskQuestionLogic::init()->selectQidRow($qid);

		($qid != $question['qid'] ? $this->_redirect('/', array('exit')) : '');

		$sortAll    = AskSortLogic::init()->selectAll(); // 取出全部分类
		$sortDetail = HelpClass::getSortDetail($sortAll, $question['sid']); // 当前分类详情

		$total  = $question['reply']; // 问题含有的回复数
		$paging	= new Paging(array('totalRs' => $total, 'perPage' => 10));
		$reply  = (0 < $total ? AskReplyLogic::init()->selectQidAll($qid, $paging->limit()) : '');

		$this->view->headTitle($question['title']);
		$this->view->headScript()->appendFile('/static/scripts/help/question/detail.js');
		$this->view->geneal   = HelpClass::getSortGeneal($sortAll, $question['sid']);
		$this->view->path     = HelpClass::getSortPath($sortAll, $sortDetail['sid']);
		$this->view->question = $question;
		$this->view->total    = $total;
		$this->view->paging   = $paging->show();
		$this->view->reply    = $reply;

		//$cache = CacheLogic::init();
		//$this->view->expert  = $cache->rankAskLoad('expert'); // 总专家榜
		//$this->view->actvite = $cache->rankAskLoad('active'); // 总活跃榜
	}

	/**
     * 问题补充-提交补充
     * 
     * @return void
     */
	public function doappendAction()
	{
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['uid'] = $this->_sessUid;

			if ($appendArgs = QuestionFilter::init()->append($postArgs))
			{
				AskQuestionLogic::init()->updateAppend($appendArgs);

				echo 'refresh';
			}
		}
	}
}
