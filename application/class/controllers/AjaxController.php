<?php

	class AjaxController extends Zend_Controller_Action 
	{
		function init()
		{
			$this->_sessCommon = Zend_Registry::get('sessCommon');
			$this->_sessClass = Zend_Registry::get('sessClass');
			// 获取班级id
			$this->_classId = (int)$this->getRequest()->getPost('class_id');
			// 获取用户id
			$this->_uid = $this->_sessCommon->login['uid'];
			
			$this->_helper->layout->disableLayout();
			$this->_helper->ViewRenderer->setNoRender();
		}
		
		# 一些需要管理员身份的操作先进行判断 -------------------------------
		private function isManager()
		{
			if($this->_sessClass->data[$this->_classId]['class_charge'] != $this->_uid && 
			   $this->_sessClass->data[$this->_classId]['class_member_charge'] != $this->_uid)
			return false;
			else return true;
		}
		
		# 开除班级管理员 ----------------------------------------------
		function classmemberlvldownAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				if(false == $this->isManager()) exit();  // 不是管理员
				$member_id = $request->getPost('member_id');
				// 保证有数据
				if(is_array($member_id) && count($member_id) > 0)
				{
					$db = Zend_Registry::get('dbClass');
					foreach ($member_id as $k=>$v)
					{
						$db->update('tbl_class_member',array('class_member_charge'=>0),
							array('class_member_id = '.(int)$v, 'class_id = '.$this->_classId));
					}
				}
			}
		}
		
		# 班级管理员列表 -----------------------------------------------
		function classmanagerlistAction()
		{
			if($this->getRequest()->isXmlHttpRequest())
			{
				if(false == $this->isManager()) exit();  // 不是管理员
				$this->_sessClass->default['managerMember'] = "managerList({$this->_classId})";
				$db = Zend_Registry::get('dbClass');
				$rows = $db->fetchAll('SELECT `class_member_id`,`class_charge`,`class_member_charge`,`realName`,`class_member_last_access` 
									   FROM `vi_class_member` 
									   WHERE `class_id` = ? AND `class_member_charge` = 1', $this->_classId);
				$this->view->class_id = $this->_classId;
				$this->view->members = $rows;
				$this->render('manager-list');
			}
		}
		
		# 踢出班级 -----------------------------------------------------
		function classmemberoutAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				if(false == $this->isManager()) exit();  // 不是管理员
				$member_id = $request->getPost('member_id');
				// 保证有数据
				if(is_array($member_id) && count($member_id) > 0)
				{
					$db = Zend_Registry::get('dbClass');
					foreach ($member_id as $k=>$v)
					{
						DbModel::classMemberOut($v, $this->_classId);
					}
				}
			}
		}
		
		# 提升为班级管理员 ----------------------------------------------
		function classmemberlvlupAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				if(false == $this->isManager()) exit();  // 不是管理员
				$member_id = $request->getPost('member_id');
				// 保证有数据
				if(is_array($member_id) && count($member_id) > 0)
				{
					$db = Zend_Registry::get('dbClass');
					foreach ($member_id as $k=>$v)
					{
						$db->update('tbl_class_member',array('class_member_charge'=>1),
							array('class_member_id = '.(int)$v, 'class_id = '.$this->_classId));
					}
				}
			}
		}
		
		# 班级成员列表 ---------------------------------------------------
		function classmemberlistAction()
		{
			if($this->getRequest()->isXmlHttpRequest())
			{
				if(false == $this->isManager()) exit();  // 不是管理员
				$this->_sessClass->default['managerMember'] = "memberList({$this->_classId})";
				$db = Zend_Registry::get('dbClass');
				$rows = $db->fetchAll('SELECT `class_member_id`,`class_charge`,`class_member_charge`,`realName`,`class_member_last_access` 
									   FROM `vi_class_member` 
									   WHERE `class_id` = ?', $this->_classId);
				$this->view->class_id = $this->_classId;
				$this->view->members = $rows;
				$this->render('member-list');
			}
		}
		
		# 班级申请删除 -------------------------------------------------
		function classapplydelAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				if(false == $this->isManager()) exit();  // 不是管理员
				$apply_id = $request->getPost('apply_id');
				// 保证有数据
				if(is_array($apply_id) && count($apply_id) > 0)
				{
					foreach ($apply_id as $k=>$v)
					{
						DbModel::applyDel($v);
					}
				}
			}
		}
		
		# 班级申请批准 -------------------------------------------------
		function classapplypassAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				if(false == $this->isManager()) exit();  // 不是管理员
				$apply_id = $request->getPost('apply_id');
				$member_id = $request->getPost('member_id');
				// 保证有数据
				if(is_array($apply_id) && count($apply_id) > 0)
				{
					foreach ($apply_id as $k=>$v)
					{
						if(false == DbModel::applyPass($v,$this->_classId,$member_id[$k]))
						{
							echo "批准过程中程序发生错误,请联系网站管理人员!";
							exit();
						}
					}
				}
			}
		}
		
		# 班级申请加入的详细信息查看 --------------------------------------
		function classapplydetailAction()
		{
			if($this->getRequest()->isXmlHttpRequest())
			{
				if(false == $this->isManager()) exit();  // 不是管理员
				$apply_id = $this->getRequest()->getPost('apply_id');
				$db = Zend_Registry::get('dbClass');
				$row = $db->fetchRow('SELECT `class_apply_content` FROM `vi_class_apply` WHERE `class_apply_id` = ?', $apply_id);
				echo stripslashes($row['class_apply_content']);
			}
		}
		
		# 班级申请加入列表 -----------------------------------------------
		function classapplylistAction()
		{
			if($this->getRequest()->isXmlHttpRequest())
			{
				if(false == $this->isManager()) exit();  // 不是管理员
				$this->_sessClass->default['managerMember'] = "applyList({$this->_classId})";
				$db = Zend_Registry::get('dbClass');
				$rows = $db->fetchAll('SELECT * FROM `vi_class_apply` WHERE `class_id` = ?', $this->_classId);
				$this->view->class_id = $this->_classId;
				$this->view->applies = $rows;
				$this->render('apply-list');
			}
		}
		
		# 班级公告修改动作 -----------------------------------------------
		function classnoticemodAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				$class_notice = Commons::html2str(strip_tags(trim($request->getPost('notice_content'))));
				if(false == $this->isManager()) exit();  // 不是管理员
				$db = Zend_Registry::get('dbClass');
				if($db->update('tbl_class',
								array('class_notice'=>$class_notice),
								'class_id = '.$this->_classId))
				$this->view->content = $class_notice;
				$this->view->class_id = $this->_classId;
				$this->render('class-notice');
			}
		}
		
		# 编辑班级公告获取 -----------------------------------------------
		function classnoticefetchAction()
		{
			if($this->getRequest()->isXmlHttpRequest())
			{
				if(false == $this->isManager()) exit();  // 不是管理员
				$db = Zend_Registry::get('dbClass');
				$row = $db->fetchRow('SELECT `class_notice` FROM `tbl_class` 
							   		  WHERE `class_id` = ?',$this->_classId);
				$this->view->content =strip_tags($row['class_notice']);
				$this->view->class_id = $this->_classId;
				$this->render('class-notice');
			}
		}
		
		# 加入班级连接反馈 ------------------------------------------------
		function joinAction()
		{
			if($this->getRequest()->isXmlHttpRequest())
			{
				// 判断是否已经是该班级成员
				if(false != DbModel::isJoined($this->_classId, $this->_uid))
				{
					$this->render('join-already');
				}
				elseif (false != DbModel::isApplied($this->_classId, $this->_uid))
				{
					$this->render('join-stop');
				}
				else // 写加入申请报告
				{
					$this->view->class_id = $this->_classId;
					$this->render('join-apply');
				}
			}
		}
		
		# 申请加入班级表单 ------------------------------------------------
		function joinapplyAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				$class_apply_content = Commons::html2str(strip_tags(trim($request->getPost('apply_content'))));
				
				// 判断是否已经是该班级成员
				if(false != DbModel::isJoined($this->_classId, $this->_uid))
				{
					echo '您已经是班级成员';
				}
				elseif (false != DbModel::isApplied($this->_classId, $this->_uid))
				{
					echo '您已经提交了加入申请';
				}
				else // 写入数据库
				{
					$data = array(
						'class_id' => $this->_classId,
						'class_member_id' => $this->_uid,
						'class_apply_content' => $class_apply_content,
						'class_apply_time' => time()
					);
					if(false != DbModel::insertApply($data))
					{
						echo '成功完成申请！请等待审核';
					}
				}
			}
		}
	}