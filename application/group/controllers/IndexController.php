<?php

/**
 * 群组的首页 IndexController
 * 
 * @author zjuhz.com
 * @version 
 */

class IndexController extends Zend_Controller_Action
{
	function init()
	{
	}
	
	# 首页展示
	public function indexAction() 
	{
		
	}
	
	# 统计访问量 -------------------------------------------------
	public function clickAction()
	{
		
		$this->_helper->viewRenderer->setNoRender(true);
		
		if($this->view->passport('uid')) // 是登陆的会员才能统计
		{			
			$gid = $this->_getParam('gid',null);
			$today = date('Y-m-d');
			if($gid != null)
			{
				if(Zend_Registry::get('sessGroup')->click[$gid] == null)
				{
					$lastday = GroupModel::info($gid, 'y_m_d');
					if($lastday == $today)
					{
						GroupModel::update(array(
							'total_click' => new Zend_Db_Expr('total_click+1'),
							'today_click' => new Zend_Db_Expr('today_click+1')
						),$gid);
					}
					else
					{
						GroupModel::update(array(
							'y_m_d' => $today,
							'yesterday_click' => GroupModel::info($gid, 'total_click'),
							'total_click' => new Zend_Db_Expr('total_click + 1'),
							'today_click' => 1
						),$gid);
					}
					Zend_Registry::get('sessGroup')->click[$gid] = true;
				}
			}
		}
	}
	
	# 顶部提示信息 -----------------------------------------------
	public function toptipAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		if($this->_getParam('close') == 1)
		{
			Zend_Registry::get('sessGroup')->notip = true;
		}
		if(Zend_Registry::get('sessGroup')->notip != true)
		{
			// 是否有邀请函
			$invites = UserModel::fetch($this->view->passport('uid'), 'group_invite');
			$str = '<ul class="notice mgu10">';
			if(null != $invites)
			{
				$str .= '<li>有群组邀请您哦~赶快去看看，<a href="/group/my/invite">查看邀请函</a></li>';
			}
			if($str != '<ul class="notice mgu10">')
			{
				echo $str.'<p class="txtr"><a href="javascript:notip()">知道了，不再提示我</a></p></ul>';
			}
		}
	}
}
