<?php

/**
 * PmController 发送群组短信息
 * 
 * @author zjuhz.com
 * @version 
 */

class PmController extends Zend_Controller_Action {
	
	function init()
	{
		$this->view->uid = $this->_getParam('uid', null);
	}
	
	public function noreadAction()
	{
		echo Zend_Json::encode(array('num'=>GroupPmModel::no_read_num(Cmd::myid())));
	}
	
	# 删除
	public function deleteAction()
	{
		$type = $this->_getParam('type');
		$pmArr = $this->_getParam('pm', array());
		$Pm = new GroupPmModel('dbGroup');
		if(count($pmArr) > 0)
		{
			foreach($pmArr as $id)
			{
				$row = $Pm->fetchRow('pm_id = '. $id);
				$row->delete();
			}
		}
		echo '<div class="success">成功删除</div>';
		echo Commons::js_jump('/group/pm/box?type='.$type, 1);
	}
	
	# 清除
	public function removeAction()
	{
		$type = $this->_getParam('type');
		$pmArr = $this->_getParam('pm', array());
		$Pm = new GroupPmModel('dbGroup');
		if(count($pmArr) > 0)
		{
			foreach($pmArr as $id)
			{
				$row = $Pm->fetchRow('pm_id = '. $id);
				if($type == 'receive')
				$row->to_del = 1;
				if($type == 'send')
				$row->from_del = 1;
				
				$row->save();
			}
		}
		echo '<div class="success">成功清除</div>';
		echo Commons::js_jump('/group/pm/box?type='.$type, 1);
	}
	
	# 系统自动发送信息
	public function systemAction()
	{
		$PM = new GroupPmModel('dbGroup');
		$type = $this->_getParam('type');
		$this->_helper->layout->disableLayout();
		$this->getResponse()->insert('nav','');
		$this->_helper->viewRenderer->setNoRender(false);
		switch($type)
		{
			case 'friend' : // 加为好友
				$data = array(
					'title' => '(系统发送) - '.UserModel::fetch(Cmd::myid(), 'realName').'将您加为了好友',
					'content' => $this->view->render('pm/system-friend.phtml'),
					'from' => 0,
					'to' => $this->view->uid,
					'time' => time()
				);
				$PM->createRow($data)->save();
				break;
			default :
				break;
		}
	}
	
	# 信件展示
	public function showAction()
	{
		$pm_id = $this->getRequest()->getPost('id');
		if(!$pm_id) return false;
		else
		{
			$PM = new GroupPmModel('dbGroup');
			$message = $PM->fetchRow('pm_id = '.$pm_id);
			$message->is_read = $this->_getParam('read',0);
			$message->save();
			echo '<h3>'.htmlspecialchars(stripcslashes($message->title)).'</h3>';
			echo '<div class="f14 pd10" style="line-height:160%">'.stripcslashes($message->content).'</div>';
		}
	}
	
	# 发件箱+收件箱
	public function boxAction()
	{
		$PM = new GroupPmModel('dbGroup');
		$type = $this->_getParam('type', 'receive');
		$page = $this->_getParam('p', 1);
		
		$this->view->send_num = GroupPmModel::send_num(Cmd::myid());
		$this->view->receive_num = GroupPmModel::receive_num(Cmd::myid());
		$this->view->no_read_num = GroupPmModel::no_read_num(Cmd::myid());
		
		switch($type)
		{
			case 'send': // 发件箱
				Page::$pagesize = 10;
				Page::create(array(
					'href_open' => '<a href="/group/box?type=send&p=%d">',
					'href_close' => '</a>',
					'num_rows' => $this->view->send_num,
					'cur_page' => $page
				));
				$this->view->sends = $PM->fetchAll($PM->select()->where('`from` = ?',Cmd::myid())->where('from_del = ?',0)->order(array('time DESC'))->Limit(Page::$pagesize,Page::$offset));
				break;
			
			case 'receive': // 收件箱
				Page::$pagesize = 10;
				Page::create(array(
					'href_open' => '<a href="/group/box?type=receive&p=%d">',
					'href_close' => '</a>',
					'num_rows' => $this->view->send_num,
					'cur_page' => $page
				));
				$this->view->receives = $PM->fetchAll($PM->select()->where('`to` = ?',Cmd::myid())->where('to_del = ?',0)->order(array('time DESC'))->Limit(Page::$pagesize,Page::$offset));
				break;
			
			default :
				break;
		}
		
		$friends = UserModel::fetch(Cmd::myid(), 'friends');
		if($friends != null)
		$this->view->friends = explode(',', $friends);
		$this->view->type = $type;
		$this->view->pagination = Page::$page_str;
	}
	
	/**
	 * 执行发送
	 * */
	public function sendAction()
	{
		$to = $this->_getParam('to');
		$title = strip_tags(trim($this->_getParam('title','无标题')));
		$content = strip_tags(trim($this->_getParam('content')));
		if(empty($content))
		{
			$this->_helper->layout->setLayout('error');
			echo '<div class="error">发送的内容不能为空!</div>';
		}
		else
		{
			$PM = new GroupPmModel('dbGroup');
			foreach($to as $uid) // 逐个发送
			{
				$data = array(
					'title' => $title,
					'content' => nl2br($content),
					'from' => Cmd::myid(),
					'to' => $uid,
					'time' => time()
				);
				$PM->createRow($data)->save();
			}
			echo Commons::js_jump('/group/pm/member?uid='.$this->view->uid.'&msg=success');
		}
	}
	
	/**
	 * 发送给群组成员+好友群发的表单
	 *
	 */
	public function memberAction()
	{
		if($this->view->uid == null)
		$this->_forward('error', 'error');
		else 
		{
			if($this->_getParam('msg') == 'success')
			$this->view->msg = '<div class="success mglf10">您的信件已经成功发送，可进入<a href="/group/pm/box?type=send">发件箱</a>查看</div>';
			$this->view->title = $this->_getParam('title');
			$this->view->username = UserModel::fetch($this->view->uid, 'realName');
			$friends = UserModel::fetch(Cmd::myid(), 'friends');
			if($friends != null)
			$this->view->friends = explode(',', $friends);
		}
	}
}
?>

