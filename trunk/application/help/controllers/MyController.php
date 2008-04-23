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
     * 我的首页
     * 
     * @return void
     */
	public function indexAction()
	{
		$this->_sessHelp->login = AskLogic::init()->entry($this->_sessUid); // 刷新SESSION
		$this->_forward('question');
	}

	/**
     * 我的问题
     * 
     * @return void
     */
	public function questionAction()
	{
		$this->view->headTitle($this->_iniHelp->head->title->my->question); // 载入标题
		$this->view->headScript()->appendFile('/static/scripts/help/my/my.js'); // 载入JS脚本

    	$type = $this->getRequest()->getParam('type');
		switch ($type)
		{
			case 'solved':
    			$status = 1;
    			$total  = $this->_sessHelp->login['solved'];
    			break;
			default:
    			$status = 0;
    			$total  = $this->_sessHelp->login['unsolved'];
    			$type   = 'unsolved';
		}

		$paging	  = new Paging(array('total' => $total, 'perpage' => 20));
		$question = MyLogic::init()->question($this->_sessUid, $status, $paging->limit());
		$this->view->ctrl     = 'question';
		$this->view->type     = $type;		
        $this->view->question = $question;
		$this->view->total    = $total;
		$this->view->paging   = $paging->show();
	}

	/**
     * 我的回答
     * 
     * @return void
     */
	public function replyAction()
	{
		$this->view->headTitle($this->_iniHelp->head->title->my->reply); // 载入标题
		$this->view->headScript()->appendFile('/static/scripts/help/my/my.js'); // 载入JS脚本

    	$type = $this->getRequest()->getParam('type');
		switch ($type)
		{
			case 'answer':
    			$status = 1;
    			$total  = $this->_sessHelp->login['answer'];
    			break;
			default:
    			$status = 0;
    			$total  = $this->_sessHelp->login['reply'];
    			$type   = 'reply';
		}

		$paging = new Paging(array('total' => $total, 'perpage' => 20));
		$reply  = MyLogic::init()->reply($this->_sessUid, $status, $paging->limit());
		$this->view->ctrl   = 'reply';
		$this->view->type   = $type;		
        $this->view->reply  = $reply;
		$this->view->total  = $total;
		$this->view->paging = $paging->show();
	}

	/**
     * 我的回答
     * 
     * @return void
     */
	public function collectionAction()
	{
		$this->view->headTitle($this->_iniHelp->head->title->my->collection); // 载入标题
		$this->view->headScript()->appendFile('/static/scripts/help/my/my.js'); // 载入JS脚本

    	$type = $this->getRequest()->getParam('type');
		switch ($type)
		{
			case 'collection':
    			$total  = $this->_sessHelp->login['collection'];
    			break;
			default:
    			$total  = $this->_sessHelp->login['collection'];
    			$type   = 'collection';
		}

		$paging     = new Paging(array('total' => $total, 'perpage' => 20));
		$collection = MyLogic::init()->collection($this->_sessUid, $paging->limit());
		$this->view->ctrl       = 'collection';
		$this->view->type       = $type;		
        $this->view->collection = $collection;
		$this->view->total      = $total;
		$this->view->paging     = $paging->show();
	}
}
