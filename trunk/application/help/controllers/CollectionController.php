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
		$this->_sessCommon = Zend_Registry::get('sessCommon'); // 载入公共SESSION
		$this->_sessHelp   = Zend_Registry::get('sessHelp'); // 载入项目SESSION

		$this->_sessUid    = $this->_sessCommon->login['uid']; // sessionUid

		$this->view->sessCommon = $this->_sessCommon; // Session资料注入
		$this->view->sessHelp   = $this->_sessHelp; // Session资料注入
	}

	/**
     * 收藏问题-数据提交
     * 
     * @return string to ajax
     */
	public function doinsertAction()
	{
		$this->_helper->viewRenderer->setNoRender(); // 禁用自动渲染视图
		$this->_helper->layout->disableLayout(); // 禁用layout

		if ($this->getRequest()->isXmlHttpRequest())
		{
			// 此处接收传递的数据数组 // next, see standard
			$postArgs = $this->getRequest()->getPost();
			// 此处可注入数据将用与判断 // next, see standard
			$postArgs['uid'] = $this->_sessUid;

			if ($insArgs = CollectionFilter::init()->insert($postArgs))
			{
				if (CollectionLogic::init()->insert($insArgs))
				{
					// 数据已过滤可直接使用
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
