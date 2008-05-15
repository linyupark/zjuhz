<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:ReplyController.php
 */


/**
 * 校友互助-问题回复
 */
class ReplyController extends Zend_Controller_Action
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
     * 互助模块配置
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
	}

	/**
     * 回复问题-数据提交
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
			$postArgs['scode'] = $this->_sessCommon->verify;
			$postArgs['uid']   = $this->_sessUid;

			$offer = (int)$this->_iniHelp->point->reply; // 回复奖励

			if ($insArgs = ReplyFilter::init()->insert($postArgs))
			{
				if (AskReplyLogic::init()->insert($insArgs))
				{
					// 更新会员回复总数和总积分
					if (AskLogic::init()->update(array('reply' => new Zend_Db_Expr('reply + 1'), 
					    'point' => new Zend_Db_Expr("point + {$offer}")), $insArgs['uid']))
					{
						// sess同步
						$this->_sessHelp->login['reply']++;
						$this->_sessHelp->login['point'] = $this->_sessHelp->login['point'] + $offer;
					}

					// 更新问题回复时间及回复总数
                    AskQuestionLogic::init()->update(array('reply' => new Zend_Db_Expr('reply + 1'), 
					    'replyTime' => $_SERVER['REQUEST_TIME']), $insArgs['qid']);

					// 积分日志
					(0 < $offer ? PointLogLogic::init()->insert(array(
					        'uid' => $this->_sessUid, 'point' => $offer, 'type' => 3)) : '');

					echo 'reload'; // 请求ajax重载页面
				}
			}
		}
	}
}
