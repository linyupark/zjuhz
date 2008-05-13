<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:QuestionController.php
 */


/**
 * 你问我答-问题帖子
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
     * 问答模块配置
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

		$this->_sessUid    = $this->_sessCommon->login['uid'];

		$this->view->sessCommon = $this->_sessCommon;
		$this->view->sessHelp   = $this->_sessHelp;
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
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();

		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();

			$point   = $this->_sessHelp->login['point'];
			$offer   = $postArgs['offer'];
			$after   = $point - $offer;
			$counter = array('sort0' => (int)$postArgs['sort0'], 'sort1' => (int)$postArgs['sort1'], 
			    'sort2' => (int)$postArgs['sort2'], 'filed' => 'question');

			$postArgs['uid']   = $this->_sessUid;
			$postArgs['point'] = $point;

			if ($insArgs = QuestionFilter::init()->insert($postArgs))
			{
				if (HelpClass::checkVerifyCode($postArgs['vcode'], $this->_sessCommon->verify))
				{
					if (AskQuestionLogic::init()->insert($insArgs))
					{
						// sessHelp内的各值对应变化
						$this->_sessHelp->login['point'] = $after; // 可用积分
						$this->_sessHelp->login['question']++; // 总问题数
						$this->_sessHelp->login['unsolved']++; // 未解决数

						// 分类数量更新
						AskSortLogic::init()->counter($counter);

						// 积分日志操作?
						if ($offer > 0)
						{
							// 数据已过滤可直接生成
							PointLogLogic::init()->insert(array('uid' => $insArgs['uid'], 
							    'point' => "-{$insArgs['offer']}", 'type' => 2, 
							));
						}

						// 写入信息提示
						$this->_sessHelp->message = $this->_iniHelp->hint->question->insertSuccess;

						echo 'message'; // 请求ajax给出提示
					}
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
		$this->_helper->viewRenderer->setNoRender(); // 禁用自动渲染视图
		$this->_helper->layout->disableLayout(); // 禁用layout

		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['quid'] = $this->_sessUid;

			$counter  = array('sort0' => (int)$postArgs['sort0'], 'sort1' => (int)$postArgs['sort1'], 
			    'sort2' => (int)$postArgs['sort2'], 'filed' => 'solved', );			

			if ($insArgs = QuestionFilter::init()->accept($postArgs))
			{
				if (($offer = AskQuestionLogic::init()->accept($insArgs)) >= 0)
				{
					// sessHelp内的各值对应变化
					$this->_sessHelp->login['unsolved']--;
					$this->_sessHelp->login['solved']++;

					// 分类数量更新
					AskSortLogic::init()->counter($counter);

					// 积分日志操作?
					if ($offer > 0)
					{
						// 数据已过滤可直接生成
						PointLogLogic::init()->insert(array('uid' => $insArgs['ruid'], 
						    'point' => $offer, 'type' => 4, 
						));
					}

					// 写入信息提示
					$this->_sessHelp->message = $this->_iniHelp->hint->reply->acceptSuccess;

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
		$qid    = (int)$this->getRequest()->getParam('qid');
		$detail = AskQuestionLogic::init()->selectQidRow($qid);
		if ($qid == $detail['qid'])
		{
			$all        = AskSortLogic::init()->selectAll(); // 取出全部分类
			$sortDetail = HelpClass::getSortDetail($all, $detail['sid']); // 当前分类详情
			$total  = $detail['reply'];
			$paging	= new Paging(array('total' => $total, 'perpage' => 10));

			//print_r(HelpClass::getSortGenealogy($all, $detail['sid']));exit;
			$this->view->total  = $total;
			$this->view->paging = $paging->show();
			$this->view->geneal = HelpClass::getSortGeneal($all, $detail['sid']);
			$this->view->detail = $detail;
			$this->view->reply  = AskReplyLogic::init()->selectQidAll($qid, $paging->limit());
			$this->view->path   = HelpClass::getSortPath($all, $sortDetail['sid']);
			$this->view->headTitle($detail['title']); // 载入标题
			$this->view->headScript()->appendFile('/static/scripts/help/question/detail.js'); // 载入JS脚本
		}
		else
		{
			$this->_forward('index', 'Index');
		}
	}
}
