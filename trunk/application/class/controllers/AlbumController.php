<?php
	/**
	 * 班级相册控制器
	 *
	 */
	class AlbumController extends Zend_Controller_Action 
	{
		function init()
		{
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
					echo '<script>$.facebox("<img src=\'/static/images/icon/stop.gif\' align=\'absmiddle\' /> '.implode('<br />',$tips).'")</script>';
				}
				else // 文件已经上传成功,写数据库操作 
				{
					$inputChians = new InputChains();
					$inputChians->noEmpty($request->getPost('name'),'照片名称');
					// 输入数据有错误
					if(count($inputChians->getMessages()) > 0)
					{
						// 删除上传的图片
						@unlink(DOCROOT.'/static/classes/'.$this->view->class_id.'/album/'.Upload::fetchParam('file_name'));
						$tips = $inputChians->getMessages();
						echo '<script>$.facebox("<img src=\'/static/images/icon/stop.gif\' align=\'absmiddle\' /> '.implode('<br />',$tips).'")</script>';
					}
					else // 数据库操作
					{
						
					}
				}
			}
		}
		
		/**
		 * 显示照片列表
		 *
		 */
		public function listAction()
		{

		}
		
		/**
		 * 相册详细信息查看
		 */
		public function viewAction()
		{
			
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
