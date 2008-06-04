<?php
	/**
	 * 班级相册控制器
	 *
	 */
	class AlbumController extends Zend_Controller_Action 
	{
		function init()
		{
			// 加载JS插件
			$this->view->headScript()->appendFile('/static/scripts/thickbox-compressed.js');
			
			$this->view->class_id = (int)$this->_getParam('c'); // 班级id
			if(!$this->view->class_id) //没有指定正确的参数
			{
				$this->_redirect('/home');
			}
			$this->view->class_base_info = DbModel::getClassInfo($this->view->class_id);
		}
		
		/**
		 * 班级相册首页显示部分(ajax调用)
		 */
		public function indexAction() 
		{
			
		}
		
		/**
		 * 发布个人新相册
		 */
		public function publicAction()
		{
			
			$this->view->title = '发布新照片';
			$this->render('index');
			
			$request = $this->_request;
			if($request->isPost()) // 提交了新照片
			{
				// 建立目录
				$album_dir = DOCROOT.'/static/classes/'.$this->view->class_id.'/album/';
				if(FALSE == is_dir($album_dir))
				{
					@mkdir($album_dir, 0777);
				}
				
				$up_config = array(
					'max_size' => 200,
					'allow_type' => 'jpg|jpeg|gif',
					'upload_path' => $album_dir
				);
				
				Upload::init($up_config);
				if(FALSE == Upload::handle())
				{
					$tips = Upload::getTip();
				}
				else // 文件已经上传成功,写数据库操作 
				{
					$file = $album_dir.Upload::fetchParam('file_name');
					$inputChians = new InputChains();
					$name = $inputChians->noEmpty($request->getPost('name'),'照片名称');
					$tags = $request->getPost('tag');
					$description = $request->getPost('description');
					$category = $request->getPost('category');
					$public = $request->getPost('public');
					
					$this->view->f = urldecode($category); // 保存当前选择的分类
					
					// 输入数据有错误
					if(count($inputChians->getMessages()) > 0)
					{
						// 删除上传的图片
						@unlink($file);
						$tips = $inputChians->getMessages();
					}
					else // 数据库操作
					{
						$data = array(
							'class_id' => $this->view->class_id,
							'class_album_publisher'=>$this->view->passport('uid'),
							'class_album_is_personal' => 0, // 是否为个人相册图片
							'class_album_name' => $name,
							'class_album_public' => (int)$public,
							'class_album_category' => urldecode($category),
							'class_album_pub_time' => time(),
							'class_album_filename' => Upload::fetchParam('file_name'),
							'class_album_description' => $description,
							'class_album_tag' => implode(',',$tags)
						);
						if(false == AlbumModel::insert($data))
						{
							// 删除上传的图片
							@unlink($file);
							$tips = '照片数据写入失败';
						}

						else $tips = '照片"<a title="'.$description.'" href="/static/classes/'.$this->view->class_id.'/album/'.Upload::fetchParam('file_name').'" class="thickbox">'.$name.'</a>"发布成功!';
					}
				}
				if(is_array($tips))
				$this->view->tips = implode('<br />',$tips);
				else $this->view->tips = $tips;
			}
			
			$this->render('public');
		}
		
		public function snapshotAction()
		{
			// 获取所有相册分类
			$categories = AlbumModel::fetchCategory($this->view->class_id);
			$result['未分类'] = AlbumModel::fetchItemNum($this->view->class_id, '未分类');
			foreach ($categories as $val)
			{
				$cate = $val['class_album_category'];
				$result[$cate] = AlbumModel::fetchItemNum($this->view->class_id, $cate);
			}
			$this->view->result = $result;
		}
		
		/**
		 * 显示照片列表
		 *
		 */
		public function listAction()
		{
			$request = $this->_request;
			$class_id = $this->view->class_id;
			
			// 默认获取未分类图片
			$category = urldecode($request->getParam('f'));
			if(null == $category)
			$this->_forward('snapshot');
			
			// 开始获取
			$page = (int)$request->getParam('p',1); //分页信息
			$pagesize = 10; // 设置分页大小
			Page::$pagesize = $pagesize; 
			$result = AlbumModel::fetchItem($class_id,$category,$pagesize,$page);
			
			Page::create(array(
				'href_open' => '<a href="/class/album/list?c='.$class_id.'&f='.$request->getParam('f').'&p=%d">',
				'href_close' => '</a>',
				'num_rows' => $result['numrows'],
				'cur_page' => $page
			));
			
			echo '<a href="/class/album/list?c='.$class_id.'&f='.urlencode($category).'&p=%d">';
			
			// 分配视图变量
			$this->view->f = $category;
			$this->view->numrows = $result['numrows'];
			$this->view->items = $result['rows'];
			$this->view->pagination = Page::$page_str;
		}
		
		/**
		 * 相册详细信息查看
		 */
		public function viewAction()
		{
			$request = $this->getRequest();
			$album_id = (int)$request->getParam('aid');
			echo $album_id;
			/*
			// 获取话题可看人群范围(阅读权限判断)
			$album = AlbumModel::fetchDetail($album_id);
			if($album['class_album_public'] == 0 && !Cmd::isMember($this->view->class_id))
			{
				//没有权力阅读
				$this->view->headTitle('没有权限查看该班级相册');
				$this->render('nopower');
			}
			else // 有权力阅读
			{
				$this->view->album = $album;
				$this->view->title = $album['class_album_name'];
				
				$sibling = AlbumModel::sibling($album['class_album_category'],$album_id);
				
				// 前后相片
				$this->view->previous = $sibling['previous'];
				$this->view->next = $sibling['next'];
				
				// 回复部分
				$pagesize = 5;
				$page = (int)$request->getParam('p',1);
				$rows = AlbumModel::fetchReply($album_id, $pagesize, $page);
				Page::$pagesize = $pagesize;
				Page::create(array(
					'href_open' => '<a href="/class/album/view?c='.
									$this->view->class_id.'&aid='.
									$album_id.'&p=%d">',
					'href_close' => '</a>',
					'num_rows' => $rows['numrows'],
					'cur_page' => $page
				));
				$this->view->pages = Page::$num_pages;
				if($rows['numrows']%$pagesize == 0 || Page::$num_pages == 0)
				$this->view->pages += 1;
				$this->view->page = $page;
				$this->view->pagination = Page::$page_str;
				$this->view->replies = $rows['rows'];
				$this->view->album_id = $album_id;
			}*/
		}
		
		/**
		 * 对相册进行在线的修改操作
		 */
		public function handleAction()
		{
			
		}
		
		/**
		 * 对自己提交的相册进行删除操作
		 */
		public function deleteAction()
		{
			
		}

	}
