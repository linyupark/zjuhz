<?php 

	class AdminController extends Zend_Controller_Action 
	{
		function init()
		{
			$this->view->headTitle('浙江大学杭州校友会管理后台');
			$this->view->headLink()->appendStylesheet('/static/styles/info_console.css','screen');
			
			// 获取全局SESSION 
			$this->_sessCommon = Zend_Registry::get('sessCommon');
			
			// info专署SESSION
			$this->_sessInfo = Zend_Registry::get('sessInfo');
			
			// 分配后台布局
			$this->_helper->layout->setLayout('info-console');
			
			// 单数据表对象
			$this->Entity = new EntityModel();
			$this->Category = new CategoryModel();
			
			// 必要的view变量
			$this->view->acl = Zend_Registry::get('acl'); 
			$this->view->role = Zend_Registry::get('sessCommon')->role;
			$this->view->categories = $this->Category->fetchByPower();
		}
		
		function indexAction()
		{
			// 渲染控制连接条
			$this->render('bar', null, true);
		}
		
		# 信息列表
		function entitylistAction()
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
				"href_open" => "<a href='/info/admin/entity_list/?cate_id={$category_id}&p=%d'>", 
				"href_close" => "</a>", 
				"num_rows" => count($row_set),
				"cur_page" => $this->_getParam('p',1)));
			
			$this->view->pagination = Page::$page_str;
		
			//根据分页获取相关信息
			$db = Zend_Registry::get('dbInfo');
			$select = $db->select()->from(array('e'=>'tbl_entity'),
									  array('entity_id','entity_title','entity_pub_time'))
							   		->join(array('u'=>'tbl_user'),
								      'e.user_id = u.user_id',
									  array('user_name'));
			if($where != null) $select->where($where);
			$this->view->rows = $db->fetchAll($select);
			$this->render('entity-list');
		}
		
		# 增加信息
		function entityaddAction()
		{
			//处理提交信息
			if($this->getRequest()->isPost())
			{
				// 过滤校验类加载
				$inputChains = new InputChains();
				
				$title = $inputChains->entityTitle($this->getRequest()->getPost('title'),'title');
				$content = $inputChains->entityContent($this->getRequest()->getPost('content'),'content');
				$pub_time = $inputChains->entityDate($this->getRequest()->getPost('pub_time'),'pub_time');
				$tag = $inputChains->entityTag($this->getRequest()->getPost('tag'),'tag');
				$cate_id = $this->getRequest()->getPost('cate_id');
				
				// 校验信息结果
				if(count($inputChains->getMessages()) > 0)
				$this->view->tips = implode('<br />',$inputChains->getMessages());
				
				if($this->view->tips == null) //数据输入
				{
					$data = array(
						'entity_title' => $title,
						'category_id' => $cate_id,
						'user_id' => $this->_sessInfo->user_id,
						'entity_pub_time' => $pub_time,
						'entity_content' => $content,
						'entity_tag' => $tag
					);
					if($this->Entity->insert($data))
					$this->view->tips = "信息添加成功";
				}
			}
		
			$this->view->title = stripslashes($title);
			$this->view->content = stripslashes($content);
			$this->view->cate_id = $cate_id;
			$this->view->pub_time = Commons::date($pub_time);
			$this->view->tag = $tag;
			
			// 渲染控制连接条
			$this->render('bar', null, true);
			$this->render('entity-add');
		}
		
		# 修改信息
		function entitymodAction()
		{
			// 渲染控制连接条
			$this->render('bar', null, true);
			
			// 没有指定修改的信息id就显示列表
			if(!$entity_id = $this->_getParam('id'))
			$this->_forward('entity_list');
			// 指定了id开始显示修改表单
			else 
			{
				//处理提交信息
				if($this->getRequest()->isPost())
				{
					// 过滤校验类加载
					$inputChains = new InputChains();
					
					$title = $inputChains->entityTitle($this->getRequest()->getPost('title'),'title');
					$content = $inputChains->entityContent($this->getRequest()->getPost('content'),'content');
					$pub_time = $inputChains->entityDate($this->getRequest()->getPost('pub_time'),'pub_time');
					$tag = $inputChains->entityTag($this->getRequest()->getPost('tag'),'tag');
					$cate_id = $this->getRequest()->getPost('cate_id');
				
					// 校验信息结果
					if(count($inputChains->getMessages()) > 0)
					$this->view->tips = implode('<br />',$inputChains->getMessages());
				
					if($this->view->tips == null) //数据输入
					{
						$data = array(
							'entity_title' => $title,
							'category_id' => $cate_id,
							'entity_mod_time' => $pub_time,
							'entity_content' => $content,
							'entity_tag' => $tag
						);
						$where = $this->Entity->getAdapter()->quoteInto('entity_id = ?', $entity_id);
						if($this->Entity->update($data, $where))
						$this->view->tips = "信息修改成功";
						else $this->view->tips = "没有做任何修改";
					}
				}
				
				$this->render('entity-mod');
			}
		}
	}