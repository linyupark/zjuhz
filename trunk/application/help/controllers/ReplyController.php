<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:ReplyController.php
 */


/**
 * 你问我答-回复帖子
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

			$point = $this->_sessHelp->login['point'];
			$offer = $this->_iniHelp->point->reply; // 回复奖励
			$after = $point + $offer; // 当前分加奖励分

			$postArgs['uid']   = $this->_sessUid;
			$postArgs['offer'] = $offer;

			if ($insArgs = ReplyFilter::init()->insert($postArgs))
			{
				if (HelpClass::checkVerifyCode($postArgs['vcode'], $this->_sessCommon->verify))
				{
					if (AskReplyLogic::init()->insert($insArgs))
					{
						// sessHelp内的各值对应变化
						$this->_sessHelp->login['point'] = $after; // 可用积分
						$this->_sessHelp->login['reply']++; // 总回答数

						// 积分日志操作?
						if ($offer > 0)
						{
							PointLogLogic::init()->insert(array(
							    'uid' => $this->_sessUid, 'point' => $offer, 'type' => 3, 
							));
						}

						echo 'reload'; // 请求ajax重载页面
					}
				}
			}
		}
	}
}
