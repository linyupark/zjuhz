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
	
	#　更新用户最后在线时间 -----------------------------------------
	public function useractiveAction()
	{
		$this->_helper->viewRenderer->setNoRender(true);
		// 更新用户表的last_active
		if($this->view->passport('uid'))
		UserModel::lastActive($this->view->passport('uid'));
	}
	
	# 首页展示
	public function indexAction() 
	{
		Cmd::flushGroupSession();
	}
	
	# 统计访问量 -------------------------------------------------
	public function clickAction()
	{
		
		$this->_helper->viewRenderer->setNoRender(true);
		
		if($this->view->passport('uid')) // 是登录的会员才能统计
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
							'yesterday_click' => GroupModel::info($gid, 'today_click'),
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
			$output = '';
			$gid = $this->_getParam('gid', null);
			
			// 邀请函提示
			if(UserModel::fetch($this->view->passport('uid'), 'group_invite'))
			{
				$output .= '<li>有群组邀请您哦~赶快去看看，<a href="/group/my/invite">查看邀请函</a></li>';
			}
			// 完善资料填写
			if(!$this->view->passport('eMail'))
			{
				$output .= '<li>完善联系方式以便我们与您取得联系<a href="/member/my/user/type/contact/">点击进入</a>(*电子邮箱必填)</li>';
			}
			// 群组加入审批提示
			if($gid != null && Cmd::isManager($gid) && GroupModel::info($gid, 'apply') != null)
			{
				$output .= '<li>有校友想加入你所在的群组哦~，<a href="/group/invite/apply?gid='.$gid.'">查看详细</a></li>';
			}
			if($output != '')
			echo '<ul class="notice mgu10">'.$output.'<a style="float:right;margin-top:-15px;" href="javascript:notip()">不再提醒</a></ul>';
		}
	}
}
