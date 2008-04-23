<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:SortController.php
 */


/**
 * 你问我答-问题分类
 */
class SortController extends Zend_Controller_Action
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
     * sort分类编号
     *
     * @var integer
     */
	private $_sortId = 0;

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
		$this->_sortId     = (int)$this->getRequest()->getParam('sid'); // sortId

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
     * 发布问题-分类联动
     *
     * @return void
     */
	public function jsonAction()
	{		
		$this->_helper->viewRenderer->setNoRender(); // 禁用自动渲染视图
		$this->_helper->layout->disableLayout(); // 禁用layout

		echo ($this->getRequest()->isXmlHttpRequest() ? 
		    Zend_Json::encode(SortLogic::init()->fetchPairs($this->_sortId)) : '');
	}

	/**
     * 查看分类列表
     *
     * @return void
     */
	public function browseAction()
	{
		$all    = SortLogic::init()->fetchAll(); // 取出全部分类
		$detail = HelpClass::getSortDetail($all, $this->_sortId); // 当前分类详情
		if ($this->_sortId == $detail['sid'])
		{
			$total  = $detail['question'] - $detail['closed'] - $detail['overtime'];
			$paging	= new Paging(array('total' => $total, 'perpage' => 25));

			$this->view->total  = $total;
			$this->view->detail = $detail;
			$this->view->paging = $paging->show();
			$this->view->map    = HelpClass::getSortMap($all, $this->_sortId);
			$this->view->path   = HelpClass::getSortPath($all, $this->_sortId);
			$this->view->list   = SortLogic::init()->browse($this->_sortId, $paging->limit());
			$this->view->headTitle($detail['name']);
			$this->view->headScript()->appendFile('/static/scripts/help/sort/browse.js'); // 载入JS脚本
		}
		else
		{
			$this->_forward('index', 'Index');
		}
	}

	/**
     * 查看最新列表
     *
     * @return void
     */
	public function latestAction()
	{
		$all    = SortLogic::init()->fetchAll(); // 取出全部分类
		$detail = HelpClass::getSortDetail($all, $this->_sortId); // 当前分类详情
		if ($this->_sortId == $detail['sid'])
		{
			$total  = $detail['question'] - $detail['solved'] - $detail['closed'] - $detail['overtime'];
			$paging	= new Paging(array('total' => $total, 'perpage' => 25));

			$this->view->total  = $total;
			$this->view->detail = $detail;
			$this->view->paging = $paging->show();
			$this->view->map    = HelpClass::getSortMap($all, $this->_sortId);
			$this->view->path   = HelpClass::getSortPath($all, $this->_sortId);
			$this->view->list   = SortLogic::init()->latest($this->_sortId, $paging->limit());
			$this->view->headTitle($detail['name']);
			$this->view->headScript()->appendFile('/static/scripts/help/sort/latest.js'); // 载入JS脚本
		}
		else
		{
			$this->_forward('index', 'Index');
		}
	}

	/**
     * 查看高分列表
     *
     * @return void
     */
	public function offerAction()
	{
		$all    = SortLogic::init()->fetchAll(); // 取出全部分类
		$detail = HelpClass::getSortDetail($all, $this->_sortId); // 当前分类详情
		if ($this->_sortId == $detail['sid'])
		{
			$total  = $detail['question'] - $detail['solved'] - $detail['closed'] - $detail['overtime'];
			$paging	= new Paging(array('total' => $total, 'perpage' => 25));

			$this->view->total  = $total;
			$this->view->detail = $detail;
			$this->view->paging = $paging->show();
			$this->view->map    = HelpClass::getSortMap($all, $this->_sortId);
			$this->view->path   = HelpClass::getSortPath($all, $this->_sortId);
			$this->view->list   = SortLogic::init()->offer($this->_sortId, $paging->limit());
			$this->view->headTitle($detail['name']);
			$this->view->headScript()->appendFile('/static/scripts/help/sort/offer.js'); // 载入JS脚本
		}
		else
		{
			$this->_forward('index', 'Index');
		}
	}

	/**
     * 查看高分列表
     *
     * @return void
     */
	public function forgetAction()
	{
		$all    = SortLogic::init()->fetchAll(); // 取出全部分类
		$detail = HelpClass::getSortDetail($all, $this->_sortId); // 当前分类详情
		if ($this->_sortId == $detail['sid'])
		{
			$total  = $detail['question'] - $detail['solved'] - $detail['closed'] - $detail['overtime'];
			$paging	= new Paging(array('total' => $total, 'perpage' => 25));

			$this->view->total  = $total;
			$this->view->detail = $detail;
			$this->view->paging = $paging->show();
			$this->view->map    = HelpClass::getSortMap($all, $this->_sortId);
			$this->view->path   = HelpClass::getSortPath($all, $this->_sortId);
			$this->view->list   = SortLogic::init()->forget($this->_sortId, $paging->limit());
			$this->view->headTitle($detail['name']);
			$this->view->headScript()->appendFile('/static/scripts/help/sort/forget.js'); // 载入JS脚本
		}
		else
		{
			$this->_forward('index', 'Index');
		}
	}

	/**
     * 查看解决列表
     *
     * @return void
     */
	public function solvedAction()
	{
		$all    = SortLogic::init()->fetchAll(); // 取出全部分类
		$detail = HelpClass::getSortDetail($all, $this->_sortId); // 当前分类详情
		if ($this->_sortId == $detail['sid'])
		{
			$total  = $detail['solved'];
			$paging	= new Paging(array('total' => $total, 'perpage' => 25));

			$this->view->total  = $total;
			$this->view->detail = $detail;
			$this->view->paging = $paging->show();
			$this->view->map    = HelpClass::getSortMap($all, $this->_sortId);
			$this->view->path   = HelpClass::getSortPath($all, $this->_sortId);
			$this->view->list   = SortLogic::init()->solved($this->_sortId, $paging->limit());
			$this->view->headTitle($detail['name']);
			$this->view->headScript()->appendFile('/static/scripts/help/sort/solved.js'); // 载入JS脚本
		}
		else
		{
			$this->_forward('index', 'Index');
		}
	}
}
