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
		$this->view->headTitle($this->_iniHelp->head->title->question->insert); // 载入标题
		$this->view->headScript()->appendFile('/static/scripts/help/question/insert.js'); // 载入JS脚本

		$this->view->title    = $this->getRequest()->getParam('title'); // title
	}

	/**
     * 发布问题-数据提交
     * 
     * @return string to ajax
     */
	public function doinsertAction()
	{		
		$this->_helper->viewRenderer->setNoRender(); // 禁用自动渲染视图
		$this->_helper->layout->disableLayout(); // 禁用layout

		if ($this->getRequest()->isXmlHttpRequest())
		{
			// 此处接收传递的数据数组
			$postArgs = $this->getRequest()->getPost(); //print_r($postArgs);exit;
			// 此处单独处理的数据单独取出
			$vCode   = $postArgs['vcode'];
			$sCode   = $this->_sessCommon->verify;
			$point   = $this->_sessHelp->login['point'];
			$offer   = $postArgs['offer'];
			$after   = $point - $offer;
			$counter = array('sort0' => (int)$postArgs['sort0'], 'sort1' => (int)$postArgs['sort1'], 
			    'sort2' => (int)$postArgs['sort2'], 'filed' => 'question', );
			// 此处可注入数据将用与判断
			$postArgs['uid']   = $this->_sessUid;
			$postArgs['point'] = $point;
			// 此处注销无用数据
			unset($this->_sessCommon->verify);

			if ($insArgs = QuestionFilter::init()->insert($postArgs))
			{
				if (HelpClass::checkVerifyCode($vCode, $sCode))
				{
					if (QuestionLogic::init()->insert($insArgs))
					{
						// sessHelp内的各值对应变化
						$this->_sessHelp->login['point'] = $after; // 可用积分
						$this->_sessHelp->login['question']++; // 总问题数
						$this->_sessHelp->login['unsolved']++; // 未解决数

						// 分类数量更新
						SortLogic::init()->counter($counter);

						// 积分日志操作?
						if ($offer > 0)
						{
							// 数据已过滤可直接生成
							LogLogic::init()->insert(array('uid' => $insArgs['uid'], 
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
     * @return string to ajax
     */
	public function doacceptAction()
	{
		$this->_helper->viewRenderer->setNoRender(); // 禁用自动渲染视图
		$this->_helper->layout->disableLayout(); // 禁用layout

		if ($this->getRequest()->isXmlHttpRequest())
		{
			// 此处接收传递的数据数组 // next, see standard
			$postArgs = $this->getRequest()->getPost();
			$counter  = array('sort0' => (int)$postArgs['sort0'], 'sort1' => (int)$postArgs['sort1'], 
			    'sort2' => (int)$postArgs['sort2'], 'filed' => 'solved', );
			// 此处可注入数据将用与判断 // next, see standard
			$postArgs['quid'] = $this->_sessUid;

			if ($insArgs = QuestionFilter::init()->accept($postArgs))
			{
				if (($offer = QuestionLogic::init()->accept($insArgs)) >= 0)
				{
					// sessHelp内的各值对应变化
					$this->_sessHelp->login['unsolved']--;
					$this->_sessHelp->login['solved']++;

					// 分类数量更新
					SortLogic::init()->counter($counter);

					// 积分日志操作?
					if ($offer > 0)
					{
						// 数据已过滤可直接生成
						LogLogic::init()->insert(array('uid' => $insArgs['ruid'], 
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
		$detail = QuestionLogic::init()->detail($qid);
		if ($qid == $detail['qid'])
		{
			$all        = SortLogic::init()->fetchAll(); // 取出全部分类
			$sortDetail = HelpClass::getSortDetail($all, $detail['sid']); // 当前分类详情
			$total  = $detail['reply'];
			$paging	= new Paging(array('total' => $total, 'perpage' => 10));

			//print_r(HelpClass::getSortGenealogy($all, $detail['sid']));exit;
			$this->view->total  = $total;
			$this->view->paging = $paging->show();
			$this->view->geneal = HelpClass::getSortGeneal($all, $detail['sid']);
			$this->view->detail = $detail;
			$this->view->reply  = ReplyLogic::init()->browse($qid, $paging->limit());
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
