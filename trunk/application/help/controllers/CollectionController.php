<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:CollectionController.php
 */


/**
 * 你问我答-收藏帖子
 */
class CollectionController extends Zend_Controller_Action
{
	/**
     * 项目Session
     *
     * @var object
     */
	private $_sessHelp = null;

	/**
     * 初始化
     * 
     * @return void
     */
	public function init()
	{
		$this->_sessHelp   = Zend_Registry::get('sessHelp');
	}

	/**
     * 收藏夹-收藏问题
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
			$postArgs['uid'] = Zend_Registry::get('sessCommon')->login['uid'];

			if ($insArgs = CollectionFilter::init()->insert($postArgs))
			{
				if (AskCollectionLogic::init()->insert($insArgs))
				{
					// 更新会员已收藏总数
					AskLogic::init()->update(array(
					    'collection' => new Zend_Db_Expr('collection + 1')), $insArgs['uid']
					);

					// sessHelp内的各值对应变化
					$this->_sessHelp->login['collection']++;

					echo 'alert'; // 请求ajax弹出提示
				}
			}
		}
	}
}
