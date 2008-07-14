<?php

/**
 * 群组相册 AlbumController
 * 
 * @author
 * @version 
 */


class AlbumController extends Zend_Controller_Action {
	
	function init()
	{
		$this->view->gid = $this->getRequest()->getParam('gid');
        $this->view->uid = Zend_Registry::get('sessCommon')->login['uid'];
        $this->view->role = GroupMemberModel::role($this->view->uid, $this->view->gid);
		$this->view->controller_name = $this->getRequest()->getControllerName();
        $this->view->action_name = $this->getRequest()->getActionName();
        $this->view->groupInfo = GroupModel::info($this->view->gid);
		$this->view->target = $_SERVER['DOCUMENT_ROOT'].'/static/groups/'.$this->view->gid.'/images/'.date('y_m_d').'/';
	}
	
	function clean()
	{
		// 清除布局元素
		$this->getHelper('layout')->disableLayout();
		$this->getResponse()->insert('nav', '');
		$this->getHelper('viewRenderer')->setNoRender();
	}
	
	/**
	 * 图片删除
	*/
	public function deleteAction()
	{
		$aid = $this->_getParam('aid');
		$pic = GroupAlbumModel::fetch($aid);
		if($pic['user_id'] == Cmd::myid() || Cmd::isManager($this->view->gid))
		{
			$dir = $_SERVER['DOCUMENT_ROOT'].'/static/groups/'.$this->view->gid.'/images/'.date('y_m_d', $pic['pubtime']).'/';
			GroupModel::update(array('photo_num' => new Zend_Db_Expr('photo_num - 1')), $this->view->gid);
			GroupAlbumModel::del($aid);
			unlink($dir.$pic['file']);
			unlink($dir.'sample_'.$pic['file']);
			echo '<div class="success">成功删除~</div>';
			echo Commons::js_jump('/group/album?gid='.$this->view->gid, 1);
		}
		else { echo '<div class="error">没有权力删除该图片</div>'; }
	}
	
    /**
     * 照片信息修改
     */ 
    public function modAction()
    {
        $aid = $this->_getParam('aid'); //图片id
        // 获取图片信息
		$pic = GroupAlbumModel::fetch($aid);
        $title = $this->_getParam('title', $pic['title']);
        $intro = $this->_getParam('intro', stripcslashes($pic['intro']));
        GroupAlbumModel::update($aid, array(
            'title' => $title,
            'intro' => $intro
        ));
        echo Commons::js_jump('/group/album/show?gid='.$this->view->gid.'&aid='.$aid);
    }
    
	/**
	 * 照片比例化显示
	*/
	public function picAction()
	{
		// 清除布局元素
		$this->clean();
		$aid = $this->_getParam('aid'); //图片id
		// 获取图片信息
		$pic = GroupAlbumModel::fetch($aid);
		// 路径
		$root = $_SERVER['DOCUMENT_ROOT'].'/static/groups/'.$this->view->gid.'/images/'.date('y_m_d', $pic['pubtime']).'/';
		$im = new Lp_Image($root.$pic['file']);
		if($im->width > 600) // 超过了显示的范围
		{
			$height = (600/$im->width)*$im->height;
			$im->abs_resize(600, $height, $root.'temp', null, true);
		}
		else // 原图输出
		{
			$im->output($root.'temp', null, null, true);
		}
	}
	
	# 图片展示页
	public function showAction()
	{
		$aid = $this->_getParam('aid');
		$this->view->aid = $aid;
		$this->view->pic = GroupAlbumModel::fetch($aid);
		$this->view->next = GroupAlbumModel::next($this->view->gid, $aid);
		$this->view->previous = GroupAlbumModel::previous($this->view->gid, $aid);
	}
	
	public function setAction()
	{
		// 同批图片识别字段
		$batch = $this->_getParam('bc', null);
		if($batch == null)
		$this->_redirect('/');
		
		
		$R = $this->getRequest();
		if($R->isPost()) // 保存设置
		{
			$id_arr = $R->getPost('ablum_id');
			$title_arr = $R->getPost('title');
			$tags_arr = $R->getPost('tags');
			$intro_arr = $R->getPost('intro');
			$V = new Lp_Valid();
			foreach($id_arr as $k => $id)
			{
				$title = $V->of($title_arr[$k], 'title', '第'.($k+1).'张图标题', 'trim|strip_tags|str_between[2,100]');
				$tags = strip_tags(trim($tags_arr[$k]));
				if($V->getMessages() != false)
				{
					$this->view->tip = '<div class="error">'.$V->getMessages().'</div>';
					break;
				}
				$data = array(
					'title' => $title,
					'intro' => strip_tags(trim($intro_arr[$k])),
					'tags' => $tags
				);
				GroupAlbumModel::update($id, $data);
				if(!empty($tags))
				{
					$temp = explode(' ', $tags);
					foreach($temp as $t)
					{
						GroupTagModel::albumAdd($this->view->gid, $t);
					}
				}
			}
			$this->view->tip = '<div class="success">保存成功~即将转向相册首页</div>'.Commons::js_jump('/group/album?gid='.$this->view->gid, 1);
		}

		// 获取上传后需要添加信息的图片
		$this->view->pics = GroupAlbumModel::fetchBatch($batch);
		$this->view->dir = '/static/groups/'.$this->view->gid.'/images/'.date('y_m_d').'/';
	}
	
	public function uploadAction()
	{
		// 清除布局元素
		$this->clean();
		
		$root = $_SERVER['DOCUMENT_ROOT'].'/static/groups/'.$this->view->gid.'/images/';
		if(!file_exists($root))
		@mkdir($root, 0777);
		
		if(!file_exists($this->view->target))
		@mkdir($this->view->target, 0777);
		
		// 同批图片识别字段
		$batch = $this->_getParam('batch');
		
		// 开始执行上传操作
		Lp_Upload::init(array(
			'max_size' => 2000,
			'allow_type' => 'jpg|gif|png',
			'upload_path' => $this->view->target
		));
		
		if(!Lp_Upload::multi('pic'))
		{
			echo '<script>parent.setTip("<div class=\'mglf10 error\'>'.Lp_Upload::getTip().'</div>");</script>';
		}
		else // 上传成功 ------------------------------------------
		{
			// 获取所有上传图片的数组
			$pics = Lp_Upload::fetchParam('file_name');
			$num = 0;
			foreach(array_unique($pics) as $pic) // 去掉重复的图片
			{
				$im = new Lp_Image($this->view->target.$pic);
				$data = array(
					'group_id' => $this->view->gid,
					'user_id' => Cmd::myid(),
					'title' => $pic,
					'file' => $pic,
					'pubtime' => time(),
					'width' => $im->width,
					'height' => $im->height,
					'size' => $im->size,
					'batch' => $batch
				);
				// 数据库导入
				GroupAlbumModel::insert($this->view->gid, $data);
				// 更新群组照片数
				GroupModel::update(array(
					'photo_num'=>new Zend_Db_Expr('photo_num + 1')
				), $this->view->gid);
				// 更新个人群组积分
				UserModel::coinMod(Cmd::myid(), '+1');
				// 增加成员在群组的活跃度
				GroupMemberModel::update(Cmd::myid(), array(
					'active' => new Zend_Db_Expr('active + 1')
				), $this->view->gid); 
				// 图片处理
				$im->abs_resize(100,75,$this->view->target.'sample_'.$im->name);
				$num ++;
			}
			// 更新最新事件
			GroupEventModel::insert(array(
				'group_id' => $this->view->gid,
				'user_id' => Cmd::myid(),
				'time' => time(),
				'type' => 2, //类型2为照片
				'url' => '/group/album?gid='.$this->view->gid, // 转到事件地址
				'name' => $num
			));
			echo '<script>parent.location.href="/group/album/set?gid='.$this->view->gid.'&bc='.$batch.'";</script>';
		}
	}
	
	public function indexAction()
	{
		$this->view->page = $this->_getParam('p',1);
	}
	
	public function newAction()
	{
		
	}

}
