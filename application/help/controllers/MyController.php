<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:MyController.php
 */


/**
 * 你问我答-我的互助
 */
class MyController extends Zend_Controller_Action
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

		$this->_sessUid = $this->_sessCommon->login['uid'];

		$this->view->sessCommon = $this->_sessCommon;
		$this->view->sessHelp   = $this->_sessHelp;
	}

	/**
     * 我的首页
     * 
     * @return void
     */
	public function indexAction()
	{
		// 刷新模块登录session
		$this->_sessHelp->login = AskLogic::init()->selectUidRow($this->_sessUid);

		$this->_forward('question');
	}

	/**
     * 我的问题
     * 
     * @return void
     */
	public function questionAction()
	{
		$this->view->headTitle($this->_iniHelp->head->titleMyQuestion);
		$this->view->headScript()->appendFile('/static/scripts/help/my/my.js');

    	$type = $this->getRequest()->getParam('type');
		switch ($type)
		{
			case 'solved':
			{
    			$status = 1;
    			break;
			}
			default:
			{
    			$status = 0;
    			$type   = 'unsolved';
    			
			}
		}

        $total    = $this->_sessHelp->login[$type];
		$paging	  = new Paging(array('total' => $total, 'perpage' => 20));
		$question = ($total > 0 ? AskQuestionLogic::init()->selectUidAll($this->_sessUid, $status, $paging->limit()) : '');

		$this->view->ctrl     = 'question';
		$this->view->type     = $type;
		$this->view->total    = $total;
		$this->view->paging   = $paging->show();
        $this->view->question = $question;
	}

	/**
     * 我的回答
     * 
     * @return void
     */
	public function replyAction()
	{
		$this->view->headTitle($this->_iniHelp->head->titleMyReply);
		$this->view->headScript()->appendFile('/static/scripts/help/my/my.js');

    	$type = $this->getRequest()->getParam('type');
		switch ($type)
		{
			case 'answer':
			{
    			$status = 1;
    			break;
			}
			default:
			{
    			$status = 0;
    			$type   = 'reply';
			}
		}

        $total  = $this->_sessHelp->login[$type];
		$paging = new Paging(array('total' => $total, 'perpage' => 20));
		$reply  = ($total > 0 ? AskReplyLogic::init()->selectUidAll($this->_sessUid, $status, $paging->limit()) : '');

		$this->view->ctrl   = 'reply';
		$this->view->type   = $type;
		$this->view->total  = $total;
		$this->view->paging = $paging->show();
        $this->view->reply  = $reply;
	}

	/**
     * 我的收藏
     * 
     * @return void
     */
	public function collectionAction()
	{
		$this->view->headTitle($this->_iniHelp->head->titleMyCollection);
		$this->view->headScript()->appendFile('/static/scripts/help/my/my.js');

    	$type = $this->getRequest()->getParam('type');
		switch ($type)
		{
			case 'collection':
			{
    			break;
			}
			default:
			{
    			$type = 'collection';
			}
		}

        $total      = $this->_sessHelp->login['collection'];
		$paging     = new Paging(array('total' => $total, 'perpage' => 20));
		$collection = ($total > 0 ? AskCollectionLogic::init()->selectUidAll($this->_sessUid, $paging->limit()) : '');;

		$this->view->ctrl       = 'collection';
		$this->view->type       = $type;
		$this->view->total      = $total;
		$this->view->paging     = $paging->show();		
        $this->view->collection = $collection;
	}
}
