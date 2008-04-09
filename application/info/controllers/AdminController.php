<?php 

	class AdminController extends Zend_Controller_Action 
	{
		function init()
		{
			// 获取全局SESSION 
			$this->_sessCommon = Zend_Registry::get('sessCommon');
			
			// info专署SESSION
			$this->_sessInfo = Zend_Registry::get('sessInfo');
			
			// 分配后台布局
			$this->_helper->layout->setLayout('info-console');
			
			// 单数据表对象
			$this->User = new UserModel();
			$this->Entity = new EntityModel();
			$this->Category = new CategoryModel();
			$this->DbModel = new DbModel();
			
			// 必要的view变量
			$this->view->acl = Zend_Registry::get('acl'); 
			$this->view->role = Zend_Registry::get('sessCommon')->role;
			$this->view->categories = $this->Category->fetchByPower();
		}
		
		function preDispatch()
		{
			$this->view->headLink()->appendStylesheet('/static/styles/info_console.css','screen');
			$this->getResponse()->insert('bar', $this->view->render('bar.phtml'));
		}
		
		function indexAction()
		{
			
		}
		
		#删除成员 ------------------------------------------
		function managerdelAction()
		{
			$this->_helper->ViewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
			$this->getResponse()->insert('bar', '');
			$request = $this->getRequest();
			if($request->isPost())
			{
				$id = (int)$request->getPost('user_id');
				$where = $this->User->getAdapter()->quoteInto('user_id = ?',$id);
				$this->User->delete($where);
				$this->Entity->delete($where);
				echo "该成员帐号删除成功！";
			}
		}
		
		# 修改成员 -----------------------------------------
		function managermodAction()
		{
			$this->view->headTitle('变动管理成员');
			$request = $this->getRequest();
			
			// 提交操作
			if($request->isPost())
			{
				$this->_helper->ViewRenderer->setNoRender(true);
				$this->_helper->layout->disableLayout();
				$this->getResponse()->insert('bar', '');
				
				$inputChains = new InputChains();
				$name = $inputChains->managerName($request->getPost('user_name'));
				$password = '';
				if($request->getPost('user_password') != '')
				$password = $inputChains->managerPassword($request->getPost('user_password'),$request->getPost('user_password2'));
				$role = $request->getPost('user_role');
				$id = $request->getPost('user_id');
				
				if(count($inputChains->getMessages()) > 0)
				echo implode('<br />',$inputChains->getMessages());
				
				else // 修改数据库
				{
					if($password != '')
					$data = array(
						'user_name'	=> $name,
						'user_password' => $password,
						'user_role' => $role
					);
					else 
					$data = array(
						'user_name'	=> $name,
						'user_role' => $role
					);
					$this->User->update($data, 'user_id ='.(int)$id);
					echo '成员"'.$name.'"资料修改成功!';
				}
			}
			
			// 获取权限集合
			$db = Zend_Registry::get('dbInfo');
			$this->view->roles = $db->fetchAll('SELECT * FROM `tbl_role`');
			$this->view->users = $this->User->fetchAll();
		}
		
		# 添加成员 -----------------------------------------
		function manageraddAction()
		{
			$this->view->headTitle('添加管理成员');
			$request = $this->getRequest();
			
			// 提交操作
			if($request->isPost())
			{
				$inputChains = new InputChains();
				$name = $inputChains->managerName($request->getPost('user_name'));
				$password = $inputChains->managerPassword($request->getPost('user_password'),$request->getPost('user_password2'));
				$role = $request->getPost('user_role');
				
				if(count($inputChains->getMessages()) > 0)
				$this->view->tips = implode('<br />',$inputChains->getMessages());
				
				else // 写入数据库
				{
					$data = array(
						'user_name'	=> $name,
						'user_password' => $password,
						'user_role' => $role
					);
					$this->User->insert($data);
					$this->view->tips = '新成员"'.$name.'"创建成功!';
				}
			}
			
			// 获取权限集合
			$db = Zend_Registry::get('dbInfo');
			$this->view->roles = $db->fetchAll('SELECT * FROM `tbl_role`');
		} 
		
		# 删除分类 -----------------------------------------
		function categorydelAction()
		{
			$this->_helper->ViewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
			$this->getResponse()->insert('bar', '');
			
			$category_id = (int)$this->getRequest()->getPost('category_id');
			
			$where = $this->Category->getAdapter()->quoteInto('category_id = ?',$category_id);
			$this->Category->delete($where);
			$this->Entity->delete($where);
			echo '该类别以及相关信息已成功删除';
		}
		
		# 修改分类 -----------------------------------------
		function categorymodAction()
		{
			$this->view->headTitle('修改分类');
			// 提交操作
			if($this->getRequest()->isPost())
			{
				$this->_helper->ViewRenderer->setNoRender(true);
				$this->_helper->layout->disableLayout();
				$this->getResponse()->insert('bar', '');
				
				$inputChains = new InputChains();
				$icon = $this->getRequest()->getPost('category_icon');
				$pub = $this->getRequest()->getPost('category_pub');
				$name = $inputChains->categoryName($this->getRequest()->getPost('category_name'));
				$id = $this->getRequest()->getPost('category_id');
				
				if(count($inputChains->getMessages()) > 0)
				echo implode('<br />',$inputChains->getMessages());
				
				else // 更新数据库
				{
					$data = array(
						'category_name'	=> $name,
						'category_icon' => $icon,
						'category_pub' => $pub
					);
					$this->Category->update($data, 'category_id = '.$id);
					echo '修改成功!';
				}
			}
			$this->view->categories = $this->Category->fetchAll();
		}
		
		# 添加分类 -----------------------------------------
		function categoryaddAction()
		{
			$this->view->headTitle('添加分类');
			// 提交操作
			if($this->getRequest()->isPost())
			{
				$inputChains = new InputChains();
				$name = $inputChains->categoryName($this->getRequest()->getPost('cate_name'));
				$pub = $this->getRequest()->getPost('cate_pub');
				$icon = $this->getRequest()->getPost('cate_icon');
				
				if(count($inputChains->getMessages()) > 0)
				$this->view->tips = implode('<br />',$inputChains->getMessages());
				
				else // 写入数据库
				{
					$data = array(
						'category_name'	=> $name,
						'category_icon' => $icon,
						'category_pub' => $pub
					);
					$this->Category->insert($data);
					$this->view->tips = $name.' 创建成功!';
				}
			}
		}
		
		# 发布信息 -----------------------------------------
		function entitypubAction()
		{
			$this->view->headTitle('发布信息');
			//未POST前显示所有未发布的信息
			if(!$this->getRequest()->isPost())
			{
				$this->view->rows = $this->DbModel->getEntityNoPub();
			}
			else 
			{
				$this->_helper->ViewRenderer->setNoRender(true);
				$this->_helper->layout->disableLayout();
				$this->getResponse()->insert('bar', '');
				$entity_id = $this->getRequest()->getPost('entity_id');
				$this->Entity->update(array('entity_pub'=>1),'entity_id = '.$entity_id);
				echo '该信息已成功发布';
			}
		}
		
		# 信息删除 -----------------------------------------
		function entitydelAction()
		{
			$this->view->headTitle('信息删除');
			// 确定操作按钮
			$this->view->action = 'entity_del';
			// 默认无渲染
			$this->_helper->ViewRenderer->setNoRender(true);
			$this->_helper->layout->disableLayout();
			// 没有post信息就显示list
			if(!$this->getRequest()->isPost())
			$this->_forward('entity_list');
			else {
				//不显示多余内容
				$this->getResponse()->insert('bar', '');
				//执行删除操作
				$entity_id = (int)$this->getRequest()->getPost('entity_id');	
				$where = $this->Entity->getAdapter()->quoteInto('entity_id = ?',$entity_id);
				if($this->Entity->delete($where))
				echo '该信息已成功删除';
			}
		}
		
		# 信息列表 -----------------------------------------
		function entitylistAction()
		{
			$category_id = $this->_getParam('cate_id',0);
			$this->view->cate_id = $category_id;
		
			//判断是否需要进行筛选
			if($category_id != 0) //选择了分类
			$where = 'category_id = '.$category_id;
			else //在权限范围内搜索
			{
				$in = '(';
				foreach ($this->view->categories as $cate)
				{
					$in .= $cate->category_id.',';
				}
				$in = substr($in, 0, -1).')';
				$where = 'category_id IN '.$in;
			}
			
			$numrows = $this->DbModel->getEntityNum($category_id);
		
			//按页获取信息列表
			Page::create(array(
				"href_open" => "<a href='/info/admin/{$this->view->action}/?cate_id={$category_id}&p=%d'>", 
				"href_close" => "</a>", 
				"num_rows" => $numrows,
				"cur_page" => $this->_getParam('p',1)));
		
			//根据分页获取相关信息
			$db = Zend_Registry::get('dbInfo');
			$select = $db->select()->from(array('e'=>'tbl_entity'),
									  array('entity_id','entity_title','entity_pub_time'))
							   		->join(array('u'=>'tbl_user'),
								      'e.user_id = u.user_id',
									  array('user_name'))
									  ->order(array('entity_pub_time DESC'))
									  ->limit(10, Page::$offset);
			
			
			if($this->view->role != 'admin')
			$select->where('e.user_id = '.$this->_sessInfo->user_id);
			$select->where($where);
			
			$this->view->pagination = Page::$page_str;
			$this->view->rows = $db->fetchAll($select);
			$this->render('entity-list');
		}
		
		# 增加信息 -----------------------------------------
		function entityaddAction()
		{
			$this->view->headTitle('增加信息');
			//处理提交信息
			if($this->getRequest()->isPost())
			{
				// 过滤校验类加载
				$inputChains = new InputChains();
				
				$title = $inputChains->entityTitle($this->getRequest()->getPost('title'));
				$content = $inputChains->entityContent($this->getRequest()->getPost('content'));
				$pub_time = $inputChains->entityDate($this->getRequest()->getPost('pub_time'));
				$tag = $inputChains->entityTag($this->getRequest()->getPost('tag'));
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
			
			$this->render('entity-add');
		}
		
		# 修改信息 -----------------------------------------
		function entitymodAction()
		{
			$this->view->headTitle('修改信息');
			// 确定操作按钮
			$this->view->action = 'entity_mod';
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
				$this->view->row = $this->Entity->fetchRow(array('entity_id = ?'=>$entity_id));
				$this->render('entity-mod');
			}
		}
	}