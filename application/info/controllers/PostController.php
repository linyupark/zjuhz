<?php

class PostController extends Zend_Controller_Action
{
	function init()
	{
		$this->_helper->layout->setLayout('info-console');
		$this->view->headTitle('信息管理模块');

		$this->Entity = new EntityModel(array('db'=>'db_info'));
		$this->Category = new CategoryModel(array('db'=>'db_info'));
		$this->sess_info = Zend_Registry::get('sess_info');
		Acl::roleCheck('post');
		$this->view->categories = $this->Category->fetchByPower();
	}

	#讯息主版 -----------------------------------------
	function indexAction()
	{
		$category_id = $this->_getParam('cate_id',0);
		$this->view->cate_id = $category_id;
		
		//判断是否需要进行筛选
		if($category_id != 0) //选择了分类
		$where = 'category_id = '.$category_id;
		else //在权限范围内搜索
		{
			$where = '';
			foreach ($this->view->categories as $cate)
			{
				$where .= "category_id = {$cate->category_id} OR ";
			}
			$where = substr($where,0,-3);
		}
		
		$row_set = $this->Entity->fetchAll($where);
		
		//按页获取信息列表
		Page::create(array(
			"href_open" => "<a href='/info/post/?cate_id={$category_id}&p=%d'>", 
			"href_close" => "</a>", 
			"num_rows" => count($row_set),
			"cur_page" => $this->_getParam('p',1)));
			
		$this->view->pagination = Page::$page_str;
		
		//根据分页获取相关信息
		$db = Zend_Registry::get('db_info');
		$select = $db->select()->from(array('e'=>'tbl_entity'),
									  array('entity_id','entity_title','entity_pub_time'))
							   ->join(array('u'=>'tbl_user'),
								      'e.user_id = u.user_id',
									  array('user_name'));
		if($where != null) $select->where($where);
		$this->view->rows = $db->fetchAll($select);
		
		$this->render('bar', null, true);
		$this->render('index');
	}

	#添加新的讯息 -------------------------------------
	function addAction()
	{
		//处理提交信息
		if($this->getRequest()->isPost())
		{
			$content = $this->getRequest()->getPost('content');
			$cate_id = $this->getRequest()->getPost('cate_id');
			$title = trim($this->getRequest()->getPost('title'));
			$pub_time = $this->getRequest()->getPost('pub_time');

			if(!Zend_Validate::is($content, 'NotEmpty') ||
			!Zend_Validate::is($title, 'NotEmpty') ||
			!Zend_Validate::is($pub_time, 'NotEmpty') ||
			!Zend_Validate::is($cate_id, 'NotEmpty') )
				$this->view->tips = "信息内容,标题,发布时间,分类ID不能为空！";
			if(!strtotime($pub_time)) 
				$this->view->tips .= "日期格式不对,请参考原格式！";
				
			if($this->view->tips == null) //数据输入
			{
				$data = array(
				'entity_title' => $title,
				'category_id' => $cate_id,
				'user_id' => $this->sess_info->user_id,
				'entity_pub_time' => strtotime($pub_time),
				'entity_content' => $content
				);
				if($this->Entity->insert($data))
				$this->view->tips = "信息添加成功";
			}
			echo '<script>$(".tips").fadeOut(1800);</script>';
		}
		
		$this->view->title = stripslashes($title);
		$this->view->content = stripslashes($content);
		$this->view->cate_id = $cate_id;
		$this->view->pub_time = $pub_time;
		$this->render('bar', null, true);
		$this->render('add');
	}

	#修改讯息 ------------------------------------------
	function modAction()
	{
		$entity_id = (int)$this->_getParam('post_id');
		
		//处理提交信息
		if($this->getRequest()->isPost())
		{
			$content = $this->getRequest()->getPost('content');
			$cate_id = $this->getRequest()->getPost('cate_id');
			$title = trim($this->getRequest()->getPost('title'));
			$pub_time = $this->getRequest()->getPost('pub_time');

			if(!Zend_Validate::is($content, 'NotEmpty') ||
			!Zend_Validate::is($title, 'NotEmpty') ||
			!Zend_Validate::is($pub_time, 'NotEmpty') ||
			!Zend_Validate::is($cate_id, 'NotEmpty') )
				$this->view->tips = "信息内容,标题,发布时间,分类ID不能为空！";
			if(!strtotime($pub_time)) 
				$this->view->tips .= "日期格式不对,请参考原格式！";
				
			if($this->view->tips == null) //数据输入
			{
				$data = array(
				'entity_title' => $title,
				'category_id' => $cate_id,
				'user_id' => $this->sess_info->user_id,
				'entity_mod_time' => strtotime($pub_time),
				'entity_content' => $content
				);
				$where = $this->Entity->getAdapter()->quoteInto('entity_id = ?', $entity_id);
				if($this->Entity->update($data, $where))
				$this->view->tips = "信息修改成功";
				else $this->view->tips = "没有做任何修改";
			}
			echo '<script>$(".tips").fadeOut(1800);</script>';
		}
		
		$this->view->row = $this->Entity->fetchRow(array('entity_id = ?'=>$entity_id));
		
		$this->render('bar', null, true);
		$this->render('mod');
	}

	#删除讯息 ------------------------------------------
	function delAction()
	{
		$this->_helper->ViewRenderer->setNoRender(true);
		$this->_helper->layout->disableLayout();
		if(!$this->getRequest()->isPost())
		exit();
		$entity_id = (int)$this->getRequest()->getPost('entity_id');	
		$where = $this->Entity->getAdapter()->quoteInto('entity_id = ?',$entity_id);
		if($this->Entity->delete($where))
		echo '该信息已成功删除';
	}
}