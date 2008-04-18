<?php

	class AjaxController extends Zend_Controller_Action 
	{
		function init()
		{
			$this->_sessCommon = Zend_Registry::get('sessCommon');
			$this->_sessClass = Zend_Registry::get('sessClass');
			$this->_helper->layout->disableLayout();
			$this->_helper->ViewRenderer->setNoRender();
		}
		
		function joinAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				$class_id = (int)$request->getPost('class_id');
				$uid = $this->_sessCommon->login['uid'];
				// 判断是否已经是该班级成员
				if(false != DbModel::isJoined($class_id, $uid))
				{
					$this->render('join-already');
				}
				elseif (false != DbModel::isApplied($class_id, $uid))
				{
					$this->render('join-stop');
				}
				else // 写加入申请报告
				{
					$this->view->class_id = $class_id;
					$this->render('join-apply');
				}
			}
		}
		function joinapplyAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				$class_id = (int)$request->getPost('class_id');
				$class_apply_content = Commons::html2str(strip_tags(trim($request->getPost('apply_content'))));
				
				// 判断是否已经是该班级成员
				if(false != DbModel::isJoined($class_id, $this->_sessCommon->login['uid']))
				{
					echo '您已经提交了加入申请';
				}
				elseif (false != DbModel::isApplied($class_id, $uid))
				{
					echo '您已经提交了加入申请';
				}
				else // 写入数据库
				{
					$data = array(
						'class_id' => $class_id,
						'class_member_id' => $this->_sessCommon->login['uid'],
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