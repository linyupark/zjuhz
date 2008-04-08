<?php

	class ViewController extends Zend_Controller_Action
	{
		function init()
		{
			$this->view->headLink()->appendStylesheet('/static/styles/home.css','screen')
								   ->appendStylesheet('/static/styles/info_front.css','screen');

			// 获取全局SESSION 
			$this->_sessCommon = Zend_Registry::get('sessCommon');
								   
			// 当前所属模块分配
			$this->view->request = $this->getRequest();
			
			// 分配当前角色信息
			$this->view->role = $this->_sessCommon->role;
			
			// 分配角色详细信息
			$this->view->accountInfo = array(
    	    	'realName'=>$this->_sessCommon->login['realName'],
    	    	'unRead'=>'0',
    		);
		}
		
		# 显示详细的文章信息
		function detailAction()
		{
			// 获取文章id
			$id = (int)$this->_getParam('id', false);
			if(!$id || $id == 0) $this->_forward('error', 'error');
			
			// 调取数据库相关信息
			else{
				$Db = new DbModel();
				$entity = $Db->getDetailInfo($id);
				//主文章
				$this->view->entity = $entity; 
				//标签
				$this->view->tag = explode(',', $entity['entity_tag']);
				//上下关联
				$this->view->sibling = $Db->getSibling($entity['entity_pub_time'], $entity['category_id']);
				//相关tag+title模糊查询
				$this->view->similarity = $Db->getSimilarity($entity['entity_id'], $entity['entity_tag']);
			}
		}
	}