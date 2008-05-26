<?php

/**
 * @category   zjuhz.com
 * @package    help
 * @copyright  Copyright(c)2008 zjuhz.com
 * @author     wangyumin
 * @version    Id:CollectionController.php
 */


/**
 * 校友互助-收藏功能
 */
class CollectionController extends Zend_Controller_Action
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

		$this->_sessUid = $this->_sessCommon->login['uid'];
	}

	/**
     * 收藏夹-收藏问题
     * 
     * @return void
     */
	public function doinsertAction()
	{
		if ($this->getRequest()->isXmlHttpRequest())
		{
			$postArgs = $this->getRequest()->getPost();
			$postArgs['uid'] = $this->_sessUid;

			if ($insArgs = CollectionFilter::init()->insert($postArgs))
			{
				if (AskCollectionLogic::init()->insert($insArgs))
				{
					// 更新会员已收藏总数
					(AskLogic::init()->update(array('collection' => new Zend_Db_Expr('collection + 1')), 
					    $insArgs['uid']) ? $this->_sessHelp->login['collection']++ : '');

					echo 'alert'; // 请求ajax弹出提示
				}
			}
		}
	}
}
