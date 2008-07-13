<?php

/**
 * @category   zjuhz.com
 * @package    biz
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:MessageController.php
 */


/**
 * 校友企业-企业留言簿
 */
class MessageController extends Zend_Controller_Action
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
	private $_sessCompany = null;

	/**
     * 当前企业编号
     * 
     * @var string
     */
	private $_dataCid = null;

	/**
     * 当前企业资料
     * 
     * @var array
     */
	private $_dataCompany = array();

	/**
     * 初始化
     * 
     * @return void
     */
	public function init()
	{
		$this->_sessCommon  = Zend_Registry::get('sessCommon');
		$this->_sessCompany = Zend_Registry::get('sessCompany');

		$this->view->sessCommon  = $this->_sessCommon;
		$this->view->sessCompany = $this->_sessCompany;
	}

	/**
     * 留言簿显示页
     * 
     * @return void
     */
	public function indexAction()
	{
		$this->_dataCid     = CommonFilter::cid(($this->getRequest()->getParam('cid'))); // 获取企业编号
		$this->_dataCompany = CorpCompanyLogic::init()->selectCidRow($this->_dataCid); // 载入企业资料

		// 判断该企业是否存在
        ($this->_dataCompany['cid'] === $this->_dataCid ? '' : $this->_redirect('/biz/', array('exit')));

        // 显示留言簿内容列表
        $paging = new Paging(array('totalRs' => $this->_dataCompany['msgs'], 'perPage' => 10));
        $this->view->message = CompanyMsgLogic::init()->selectCidNotDeletedAll($this->_dataCid, $paging->limit());
        $this->view->paging  = $paging->show();

		$this->_helper->layout->setLayout('company');
        $this->view->headTitle($this->_dataCompany['name']);
		$this->view->headScript()->appendFile('/static/scripts/biz/message/index.js');
		$this->view->headLink()->appendStylesheet('/static/styles/paging.css', 'screen');
        $this->view->dataCid     = $this->_dataCid;
        $this->view->dataCompany = $this->_dataCompany;
	}

	/**
     * 企业留言簿-发表留言
     * 
     * @return void
     */
	public function doinsertAction()
	{
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['uid'] = USER_UID;

			if ($insertArgs = MsgFilter::init()->insert($postArgs))
			{
				if (CompanyMsgLogic::init()->insert($insertArgs))
				{
					// 更新留言人总留言数
					CorpLogic::init()->update(array('uid' => $insertArgs['uid'], 
					    'msgs' => new Zend_Db_Expr('msgs + 1'))
					);

					// 更新企业的总留言数
					CorpCompanyLogic::init()->update(array('cid' => $insertArgs['cid'], 
					    'msgs' => new Zend_Db_Expr('msgs + 1'))
					);

					// 刷新当前留言人sess
					++$this->_sessCompany->login['msgs'];

					$this->_sessCompany->message = $this->_iniCompany->hint->joinSubmit;

					echo 'reload'; // 请求ajax弹出提示
				}
			}
		}
	}

	/**
     * 企业留言簿-回复留言
     * 
     * @return void
     */
	public function doreplyAction()
	{
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();

			if ($replyArgs = MsgFilter::init()->reply($postArgs))
			{
				if (CompanyMsgLogic::init()->updateReply($replyArgs))
				{
					$this->_sessCompany->message = $this->_iniCompany->hint->joinSubmit;

					echo 'reload'; // 请求ajax弹出提示
				}
			}
		}
	}

	/**
     * 企业留言簿-删除留言
     * 
     * @return void
     */
	public function dodeleteAction()
	{
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();

			if ($deleteArgs = MsgFilter::init()->delete($postArgs))
			{
				if (CompanyMsgLogic::init()->updateStatusDel($deleteArgs))
				{
					// 更新企业的总留言数
					CorpCompanyLogic::init()->update(array('cid' => $deleteArgs['cid'], 
					    'msgs' => new Zend_Db_Expr('msgs - 1'))
					);

					$this->_sessCompany->message = $this->_iniCompany->hint->joinSubmit;

					echo 'hide'; // 请求隐藏被删除部份
				}
			}
		}
	}
}
