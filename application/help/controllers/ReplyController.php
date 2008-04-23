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
	}

	/**
     * 回复问题-数据提交
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
			$offer   = $this->_iniHelp->point->reply;
			$after   = $point + $offer;
			// 此处可注入数据将用与判断
			$postArgs['uid']   = $this->_sessUid;
			$postArgs['offer'] = $offer;
			// 此处注销无用数据
			unset($this->_sessCommon->verify);

			if ($insArgs = ReplyFilter::init()->insert($postArgs))
			{
				if (HelpClass::checkVerifyCode($vCode, $sCode))
				{
					if (ReplyLogic::init()->insert($insArgs))
					{
						// sessHelp内的各值对应变化
						$this->_sessHelp->login['point'] = $after; // 可用积分
						$this->_sessHelp->login['reply']++; // 总回答数

						// 积分日志操作?
						if ($offer > 0)
						{
							// 数据已过滤可直接生成
							LogLogic::init()->insert(array(
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
