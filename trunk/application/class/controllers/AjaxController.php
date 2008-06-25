<?php

	class AjaxController extends Zend_Controller_Action 
	{
		function init()
		{
			$this->_sessClass = Zend_Registry::get('sessClass');
			// 获取班级id
			$this->_classId = (int)$this->getRequest()->getPost('class_id');
			
			$this->_helper->layout->disableLayout();
			$this->_helper->ViewRenderer->setNoRender();
		}
		
		/* 首页调用相关 ///////////////////////////////////////////////////// */
		
		// 我的班级
		function myclassAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				$uid = $this->_getParam('uid');
				$db = Zend_Registry::get('dbClass');
				$classes = $db->fetchAll('SELECT `class_name`,`class_id` FROM `vi_class_member`
							  WHERE `class_member_id` = ?',$uid);
				$str = '';
				if(count($classes) > 0)
				{
					foreach($classes as $class)
					{
						$str .= '<a href="/class/home?c='.$class['class_id'].'">'.$class['class_name'].'</a>,';
					}
					echo substr($str, 0,-1);
				}
				else echo '您还没有加入任何班级';
			}
		}
		
		
		/* 相册相关 //////////////////////////////////////////////////////////*/
		
		function changeclassnameAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				if(false == Cmd::isManager($this->_classId)) exit();  // 不是班级管理员
				$inputChains = new InputChains();
				$class_name = $inputChains->className($request->getPost('class_name'));
				if(count($inputChains->getMessages()) > 0) //数据有问题
				{
					$this->view->err_tip = $inputChains->getMessages();
					$this->render('error');
				}
				else 
				{
					$db = Zend_Registry::get('dbClass');
					$db->update('tbl_class', array('class_name' => $class_name), 'class_id='.$this->_classId);
					$this->_sessClass->data[$this->_classId] = null;
					$this->view->suc_tip = "班级名称修改成功!";
					Cmd::cacheClass($this->view->passport('uid'));
					echo Commons::js_jump('/class/home?c='.$this->_classId, 1);
					$this->render('success');
				}
			}
		}
		
		# 删除照片
		function delalbumAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				if(false == Cmd::isManager($this->_classId)) exit();  // 不是班级管理员
				$album_id = $request->getPost('album_id');
				$r = AlbumModel::delete($album_id);
				if($r != true)
				{
					$this->view->err_tip = '修改失败';
					$this->render('error');
				}
			}
		}
		
		# 处理提交的回复
		function postalbumreplyAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				$this->view->page = (int)$request->getPost('p',1);
				$this->view->album_id = (int)$request->getPost('aid');
				$this->view->class_id = $this->_classId;
				$inputChain = new InputChains();
				$album_id = (int)$request->getPost('album_id');
				$reply_title = strip_tags(trim($request->getPost('reply_title')));
				$reply_content = $inputChain->noEmpty($request->getPost('content'),'回复内容');
				if(null != $inputChain->getMessages())
				{
					// 弹出错误
					$this->view->err_tip = $inputChain->getMessages();
					$this->render('error');
				}
				else 
				{
					$db = Zend_Registry::get('dbClass');
					if($db->insert('tbl_class_reply',array(
										'class_reply_author' => $this->view->passport('uid'),
										'class_album_id' => $album_id,
										'class_reply_title' => $reply_title,
										'class_reply_content' => Commons::html2str($reply_content),
										'class_reply_time' => time())) >0)
					AlbumModel::replyNumInc($album_id);
					else 
					{
						// 弹出错误
						$this->view->err_tip = '回复失败~';
						$this->render('error');
					}
				}
			}	
		}
		
		# 显示相册回复表单
		function albumreplyformAction()
		{	
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				$this->view->page = (int)$request->getPost('p',1);
				$this->view->album_id = (int)$request->getPost('aid');
				$this->view->class_id = $this->_classId;
				$this->render('album-reply-form');
			}	
		}
		
		# 下载操作
		function downloadpicAction()
		{
			$request = $this->getRequest();
			$class_id = (int)$request->getParam('c');
			$file = $request->getParam('file');
			$name = urldecode($request->getParam('name'));
			Commons::download(DOCROOT.'/static/classes/'.$class_id.'/album/'.$file,$name.'_'.$file);
		}
		
		# 移动照片所属文件夹
		function movealbumcategoryAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				if(false == Cmd::isManager($this->_classId)) exit();  // 不是班级管理员
				$album_id = $request->getPost('album_id');
				$category = urldecode($request->getPost('change'));
				$r = AlbumModel::modCategory($album_id, $category);
				if($r != 1)
				{
					$this->view->err_tip = '修改失败';
					$this->render('error');
				}
			}
		}
		
		# 新建立照片所属文件夹
		function newalbumcategoryAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				if(false == Cmd::isManager($this->_classId)) exit();  // 不是班级管理员
				$album_id = $request->getPost('album_id');
				$new_category = $request->getPost('new');
				if(trim($new_category) == '')
				{
					$this->view->err_tip = '分类不能为空';
					$this->render('error');
				}
				else{
					$r = AlbumModel::modCategory($album_id, $new_category);
					if($r != 1)
					{
						$this->view->err_tip = '修改失败';
						$this->render('error');
					}
				}
			}
		}
		
		/* 邀请相关 ////////////////////////////////////////////////////////// */
		
		# 接受邀请
		function inviteacceptAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				$invite_id = (int)$request->getPost('invite_id');
				$member_id = (int)$request->getPost('member_id');
				if(true == InviteModel::accept($invite_id, $this->_classId,$member_id))
				{
					$this->view->suc_tip = '成功加入该班级';
					Cmd::cacheClass(member_id);
					echo Commons::js_jump('/class/home?c='.$this->_classId,1);
					$this->render('success');
				}
			}
		}
		
		# 拒绝邀请
		function inviterefuseAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				$invite_id = (int)$request->getPost('invite_id');
				$db = Zend_Registry::get('dbClass');
				$r = $db->delete('tbl_class_invite','`class_invite_id` = '.$invite_id);
				if($r == 1)
				{
					$this->view->suc_tip = '成功拒绝该班级邀请';
					echo Commons::js_jump('',1);
					$this->render('success');
				}
			}
		}
		
		# 查看邀请
		function inviteviewAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				$invite_id = (int)$request->getPost('invite_id');
				$this->view->invite = InviteModel::fetchRow($invite_id);
				$this->render('invite-view');
			}
		}
		
		# 发出邀请
		function invitesendAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				if(false == Cmd::isMember($this->_classId)) exit();  // 不是班级成员
				$from = (int)$request->getPost('from');
				$call = (int)$request->getPost('call');
				$memo = $request->getPost('invite_memo');
				if($from == 0 || $call == 0)
				{
					// 弹出错误
					$this->view->err_tip = '发送人和接受人发生异常';
					$this->render('error');
				}
				else 
				{
					$db = Zend_Registry::get('dbClass');
					$is_invited = $db->fetchRow('SELECT `class_invite_id` FROM `tbl_class_invite` 
								   				WHERE `class_id` = ? AND `class_call_id` = ?',
								   				array($this->_classId, $call));
					if(FALSE != $is_invited)
					{
						// 弹出错误
						$this->view->err_tip = '本班级已经有人邀请该校友';
						$this->render('error');
					}
					else 
					{
						$db->insert('tbl_class_invite',array(
							'class_id' => $this->_classId,
							'class_call_id' => $call,
							'class_member_id' => $from,
							'class_invite_memo' => $memo
						));
						$this->view->suc_tip = '邀请成功！';
						$this->render('success');
					}
				}
			}
		}
		
		# 弹出邀请表单
		function inviteAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				if(false == Cmd::isMember($this->_classId)) exit();  // 不是班级成员
				$this->view->class_id = $this->_classId;
				$this->view->uid = (int)trim($request->getPost('uid'));
				$this->render('invite-form');
			}
		}
		
		# 搜索名字罗列结果 - 必须为班级成员
		function invitesearchAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				if(false == Cmd::isMember($this->_classId)) exit();  // 不是班级成员
				$realname = trim($request->getPost('realname'));
				$this->view->users = UserModel::fetch($this->_classId, $realname);
				$this->view->class_id = $this->_classId;
				$this->render('invite-search');
			}
		}
		
		/* 通讯录相关 //////////////////////////////////////////////////////// */
		
		# 导出相关 - 班级成员可操作
		function addressbookexportAction()
		{
			$class_id = $this->getRequest()->getParam('c');
			if(false == Cmd::isMember($class_id)) exit();  // 不是班级成员
			$rows = AddressModel::fetch($class_id,999,1);
			$doc[1] = array('<b>姓名</b>','<b>手机</b>','<b>电子邮箱</b>','<b>QQ</b>','<b>MSN</b>','<b>通讯地址</b>','<b>邮政编码</b>','<b>座机</b>','<b>所在单位</b>');
			foreach ($rows['rows'] as $k => $v)
			{
				$doc[$k+2] = array($v['cname'],$v['mobile'],$v['eMail'],$v['qq'],$v['msn'],$v['address'],$v['postcode'],$v['addressbook_telephone'],$v['addressbook_company']);
				
			}
			$excel = new ExcelXML();
			$excel->addArray ($doc);
			$excel->generateXML('MyClassAddressBook');
		}
		
		# 导入操作开始 - 只允许创建者
		function importaddressbookAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				if(false == Cmd::isCreater($this->_classId)) exit();  // 不是班级创建者
				$records = $request->getPost('records');
				if(count($records) > 0) // 有记录
				{
					$db = Zend_Registry::get('dbClass');
					// 先删除老的记录
					$db->delete('tbl_class_addressbook',array('class_id='.$this->_classId,'uid=0'));
					foreach ($records as $key)
					{
						if($key['cid'] != null)
						{
							$data = array(
								'class_id' => $this->_classId,
								'uid' => 0,
								'cname' => $key['cname'],
								'mobile' => $key['mobile'],
								'eMail' => $key['eMail'],
								'qq' => $key['qq'],
								'msn' => $key['msn'],
								'address' => $key['address'],
								'postcode' => $key['postcode'],
							);
							$db->insert('tbl_class_addressbook',$data);
						}
					}
					$this->view->suc_tip = '成功导入数据！';
					$this->render('success');
				}
			}
		}
		
		# 显示个人通讯录 - 导入准备 - 只允许创建者使用
		function mypersonaladdressbookAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				$uid = (int)$this->view->passport('uid');
				if(false == Cmd::isCreater($this->_classId)) exit();  // 不是班级创建者
				// 显示XMLRPC数据
				$client = new Zend_XmlRpc_Client('http://xmlrpc/MemberServer.php');
				$groups = $client->call('rpcMember.AddressGroupSelectUidAll',array($uid));
				if(count($groups) > 0)
				{
					foreach ($groups as $v)
					{
						$items[$v['gid']] = $client->call('rpcMember.AddressCardSelectGidAll',array($v['gid'],$uid));
					}
				}
				else { $groups = null; $items = null; }
				$this->view->class_id = $this->_classId;
				$this->view->groups = $groups;
				$this->view->items = $items;
				$this->render('personal-addressbook');
			}
		}
		
		# 编辑自己所在班级的通讯录信息
		function myclassaddressbookAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				if(false == Cmd::isMember($this->_classId)) exit();  // 不是班级成员
				if($request->getPost('cname') != null) // 数据更新
				{
					$inputChians = new InputChains();
					$cname = $inputChians->noEmpty($request->getPost('cname'),'名称');
					$mobile = $inputChians->addressMobile($request->getPost('mobile'));
					$email = $inputChians->addressEmail($request->getPost('eMail'));
					$qq = $inputChians->addressQQ($request->getPost('qq'));
					$msn = strip_tags(trim($request->getPost('msn')));
					$address = strip_tags(trim($request->getPost('address')));
					$postcode = strip_tags(trim($request->getPost('postcode')));
					$company = strip_tags(trim($request->getPost('company')));
					$telephone = strip_tags(trim($request->getPost('telephone')));
					if($inputChians->getMessages() != null)
					{
						// 弹出错误
						$this->view->err_tip = $inputChians->getMessages();
						$this->render('error');
					}
					else // 更新数据
					{
						$result = AddressModel::update(array(
							'cname'=>$cname,
							'mobile'=>$mobile,
							'eMail'=>$email,
							'qq'=>$qq,
							'msn'=>$msn,
							'address'=>$address,
							'postcode'=>$postcode,
							'addressbook_company'=>$company,
							'addressbook_telephone'=>$telephone),array('`class_id`='.$this->_classId,'`uid`='.$this->view->passport('uid')));
						if($result != 1)
						{
							// 弹出错误
							$this->view->err_tip = '通讯没有改动或更新失败！';
							$this->render('error');
						}
						else
						{
							$this->view->suc_tip = '通讯录信息更新成功！';
							$this->render('success');
						}
					}
				}
				else // 显示表单
				{
					// 显示通讯录表单
					$db = Zend_Registry::get('dbClass');
					$this->view->addressbook = $db->fetchRow('SELECT * FROM `tbl_class_addressbook` 
																WHERE `class_id` = ? AND `uid` = ?',array($this->_classId,$this->view->passport('uid')));
					$this->render('class-addressbook-form');
				}
			}
		}
		
		# 班级通讯录查看(排除非班级成员)
		function classaddressbookviewAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				if(false == Cmd::isMember($this->_classId)) exit();  // 不是班级成员
				$page = (int)$request->getPost('page',1);
				$rows = AddressModel::fetch($this->_classId,5,$page);
				Page::$pagesize = 5;
				Page::create(array(
					'href_open'=>'<a href="javascript:classAddressbookView('.$this->_classId.',%d)">',
					'href_close'=>'</a>',
					'num_rows'=>$rows['numrows'],
					'cur_page'=>$page
				));
				$this->view->pagination = Page::$page_str;
				$this->view->rows = $rows['rows'];
				$this->render('class-addressbook-view');
			}
		}
		
		/* 话题相关 //////////////////////////////////////////////////////// */
		
		# 删除回复
		function delreplyAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				if(false == Cmd::isMember($this->_classId)) exit();  // 不是成员
				$reply_id = (int)$request->getPost('reply_id');
				$topic_id = (int)$request->getPost('topic_id');
				$db = Zend_Registry::get('dbClass');
				if($db->delete('tbl_class_reply',
						array('class_reply_id = '.$reply_id,'class_reply_author = '.$this->view->passport('uid'))) > 0)
				{
					TopicModel::replyNumCut($topic_id);
					$this->view->suc_tip = '成功删除回复';
					echo Commons::js_jump('',1);
					$this->render('success');
				}
				else 
				{
					$this->view->err_tip = '回复删除失败';
					$this->render('rerror');
				}
			}
		}
		
		# 删除话题
		function deltopicAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				if(false == Cmd::isManager($this->_classId)) exit();  // 不是管理员
				$topic_id = (int)$request->getPost('topic_id');
				$db = Zend_Registry::get('dbClass');
				if($db->delete('tbl_class_topic','class_topic_id = '.$topic_id) > 0)
				{
					$db->delete('tbl_class_reply','class_topic_id = '.$topic_id);
					$this->view->suc_tip = '成功删除话题';
					echo Commons::js_jump('/class/topic/list?c='.$this->_classId,1);
					$this->render('success');
				}
				else 
				{
					$this->view->err_tip = '话题删除失败';
					$this->render('rerror');
				}
			}
		}
		
		# 话题设为精华操作/取消操作
		function elitetopicAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				if(false == Cmd::isManager($this->_classId)) exit();  // 不是管理员
				$topic_id = (int)$request->getPost('topic_id');
				$is_elite = $request->getPost('is_elite');
				$this->view->suc_tip = '<b>设置精华!</b> 点击“精华”可撤消';
				if($is_elite == 0) $is_elite = 1;
				else 
				{
					$is_elite = 0;
					$this->view->suc_tip = '<b>撤消精华!</b> 点击“精华”可恢复';
				}
				$db = Zend_Registry::get('dbClass');
				if($db->update('tbl_class_topic',array('class_topic_elite'=>$is_elite),
								'class_topic_id='.$topic_id)==1)
				$this->render('success');
			}
		}
		
		# 话题顶置操作/取消操作
		function fixtopicAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				if(false == Cmd::isManager($this->_classId)) exit();  // 不是管理员
				$topic_id = (int)$request->getPost('topic_id');
				$is_up = $request->getPost('is_up');
				$this->view->suc_tip = '<b>顶置成功!</b> 点击“顶置”可撤消';
				if($is_up == 0) $is_up = 1;
				else 
				{
					$is_up = 0;
					$this->view->suc_tip = '<b>已撤消顶置!</b> 点击“顶置”可恢复';
				}
				$db = Zend_Registry::get('dbClass');
				if($db->update('tbl_class_topic',array('class_topic_up'=>$is_up),
								'class_topic_id='.$topic_id)==1)
				$this->render('success');
			}
		}
		
		# 修改自己的话题
		function classtopicmodAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				if(false == Cmd::isMember($this->_classId)) exit();  // 不是班级成员
				if($this->view->passport('uid') != $request->getPost('author')) exit(); // 不是自己发表
				$inputChain = new InputChains();
				$topic_id = (int)$request->getPost('topic_id');
				$topic_title = $inputChain->topicTitle($request->getPost('topic_title'));
				$topic_content = $inputChain->noEmpty($request->getPost('content'),'话题内容');
				$topic_tag = $inputChain->topicTag($request->getPost('tag'));
				$topic_public = (int)$request->getPost('topic_public');
				if(null != $inputChain->getMessages())
				{
					// 弹出错误
					$this->view->err_tip = $inputChain->getMessages();
					$this->render('error');
				}
				else 
				{
					$data = array(
						'class_topic_title' => $topic_title,
						'class_topic_content' => $topic_content,
						'class_topic_public' => $topic_public,
						'class_topic_tag' => $topic_tag,
						'class_topic_mod_time' => time()
					);
					// 开始数据更新
					$db = Zend_Registry::get('dbClass');
					if($db->update('tbl_class_topic',$data,'class_topic_id = '.$topic_id) > 0)
					// 弹出提示
					$this->view->suc_tip = '话题更新成功！';
					echo Commons::js_jump('',2);
					$this->render('success');
				}
			}
		}
		
		# 处理提交的回复
		function posttopicreplyAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				$this->view->page = (int)$request->getPost('p',1);
				$this->view->topic_id = (int)$request->getPost('tid');
				$this->view->class_id = $this->_classId;
				$inputChain = new InputChains();
				$topic_id = (int)$request->getPost('topic_id');
				$reply_title = strip_tags(trim($request->getPost('reply_title')));
				$reply_content = $inputChain->noEmpty($request->getPost('content'),'回复内容');
				if(null != $inputChain->getMessages())
				{
					// 弹出错误
					$this->view->err_tip = $inputChain->getMessages();
					$this->render('error');
				}
				else 
				{
					$db = Zend_Registry::get('dbClass');
					if($db->insert('tbl_class_reply',array(
										'class_reply_author' => $this->view->passport('uid'),
										'class_topic_id' => $topic_id,
										'class_reply_title' => $reply_title,
										'class_reply_content' => Commons::html2str($reply_content),
										'class_reply_time' => time())) >0)
					TopicModel::replyNumInc($topic_id, $this->view->passport('uid'));
					else 
					{
						// 弹出错误
						$this->view->err_tip = '回复失败~';
						$this->render('error');
					}
				}
			}	
		}
		
		# 显示话题回复表单
		function topicreplyformAction()
		{	
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				$this->view->page = (int)$request->getPost('p',1);
				$this->view->topic_id = (int)$request->getPost('tid');
				$this->view->class_id = $this->_classId;
				$this->render('topic-reply-form');
			}	
		}
		
		# 返回指定的话题回复内容页数
		function fetchtopicreplyAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				$pagesize = 5;
				$page = (int)$request->getPost('p',1);
				$topic_id = (int)$request->getPost('tid');
				$rows = TopicModel::fetchReply($topic_id, $pagesize, $page);
				Page::$pagesize = $pagesize;
				Page::create(array(
					'href_open' => '<a href="javascript:fetchTopicReply('.$this->_classId.','.$topic_id.',%d)">',
					'href_close' => '</a>',
					'num_rows' => $rows['numrows'],
					'cur_page' => $page
				));
				$this->view->pages = Page::$num_pages;
				if($rows['numrows']%$pagesize == 0)
				$this->view->pages += 1;
				$this->view->page = $page;
				$this->view->topic_id = $topic_id;
				$this->view->class_id = $this->_classId;
				$this->view->pagination = Page::$page_str;
				$this->view->replies = $rows['rows'];
				$this->render('topic-reply');
			}
		}
		
		# 班级新讨论提交
		function classtopicnewAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				if(false == Cmd::isMember($this->_classId)) exit();  // 不是班级成员
				$inputChain = new InputChains();
				$topic_title = $inputChain->topicTitle($request->getPost('topic_title'));
				$topic_content = $inputChain->noEmpty($request->getPost('content'),'话题内容');
				$topic_tag = $inputChain->topicTag($request->getPost('tag'));
				$topic_public = (int)$request->getPost('topic_public');
				if(null != $inputChain->getMessages())
				{
					// 弹出错误
					$this->view->err_tip = $inputChain->getMessages();
					$this->render('error');
				}
				else 
				{
					// 检查在同班有无重复数据
					$db = Zend_Registry::get('dbClass');
					$row = $db->fetchRow('SELECT `class_topic_id` FROM `tbl_class_topic` WHERE `class_topic_title` = ?',$topic_title);
					if(false != $row) 
					{
						$this->view->err_tip = '有相同的话题存在于本班级！请更换标题';
						$this->render('error');
					}
					else 
					{
						$data = array(
							'class_id' => $this->_classId,
							'class_topic_author' => $this->view->passport('uid'),
							'class_topic_title' => $topic_title,
							'class_topic_content' => $topic_content,
							'class_topic_pub_time' => time(),
							'class_topic_public' => $topic_public,
							'class_topic_tag' => $topic_tag
						);
						// 开始数据写入
						if($db->insert('tbl_class_topic',$data) > 0)
						// 弹出提示
						$this->view->suc_tip = '话题发表成功！';
						echo Commons::js_jump('/class/topic/list?c='.$this->_classId,2);
						$this->render('success');
					}
				}
			}
		}
		
		/* 成员管理 //////////////////////////////////////////////////////// */
		
		# 开除班级管理员
		function classmemberlvldownAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				if(false == Cmd::isCreater($this->_classId)) 
				{
					$this->view->err_tip = '只有班级创建人可以开除管理员';
					$this->render('error');
					$this->getResponse()->sendResponse();
					exit();
				}
				$member_id = $request->getPost('member_id');
				// 保证有数据
				if(is_array($member_id) && count($member_id) > 0)
				{
					$db = Zend_Registry::get('dbClass');
					foreach ($member_id as $v)
					{
						$db->update('tbl_class_member',array('class_member_charge'=>0),
							array('class_member_id = '.(int)$v, 'class_id = '.$this->_classId));
					}
				}
			}
		}
		
		# 班级管理员列表
		function classmanagerlistAction()
		{
			if($this->getRequest()->isXmlHttpRequest())
			{
				if(false == Cmd::isManager($this->_classId)) exit();  // 不是管理员
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
		
		# 踢出班级
		function classmemberoutAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				if(false == Cmd::isManager($this->_classId)) exit();  // 不是管理员
				$member_id = $request->getPost('member_id');
				// 保证有数据
				if(is_array($member_id) && count($member_id) > 0)
				{
					foreach ($member_id as $v)
					{
						MemberModel::fireOut($v, $this->_classId);
						Cmd::cacheClass($member_id);
					}
				}
			}
		}
		
		# 提升为班级管理员
		function classmemberlvlupAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				if(false == Cmd::isCreater($this->_classId)) 
				{
					$this->view->err_tip = '只有班级创建人可以提拔管理员';
					$this->render('error');
					$this->getResponse()->sendResponse();
					exit();
				}
				$member_id = $request->getPost('member_id');
				// 保证有数据
				if(is_array($member_id) && count($member_id) > 0)
				{
					$db = Zend_Registry::get('dbClass');
					foreach ($member_id as $v)
					{
						$db->update('tbl_class_member',array('class_member_charge'=>1),
							array('class_member_id = '.(int)$v, 'class_id = '.$this->_classId));
					}
				}
			}
		}
		
		# 班级成员列表
		function classmemberlistAction()
		{
			if($this->getRequest()->isXmlHttpRequest())
			{
				if(false == Cmd::isManager($this->_classId)) exit();  // 不是管理员
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
		
		/* 申请相关 //////////////////////////////////////////////////////// */
		
		# 班级申请删除
		function classapplydelAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				if(false == Cmd::isManager($this->_classId)) exit();  // 不是管理员
				$apply_id = $request->getPost('apply_id');
				// 保证有数据
				if(is_array($apply_id) && count($apply_id) > 0)
				{
					foreach ($apply_id as $v)
					{
						ApplyModel::delete($v);
					}
				}
			}
		}
		
		# 班级申请批准
		function classapplypassAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				if(false == Cmd::isManager($this->_classId)) exit();  // 不是管理员
				$apply_id = $request->getPost('apply_id');
				$member_id = $request->getPost('member_id');
				// 保证有数据
				if(is_array($apply_id) && count($apply_id) > 0)
				{
					foreach ($apply_id as $k=>$v)
					{
						if(false == ApplyModel::pass($v,$this->_classId,$member_id[$k]))
						{
							echo "批准过程中程序发生错误,请联系网站管理人员!";
							exit();
						}
					}
				}
			}
		}
		
		# 班级申请加入的详细信息查看
		function classapplydetailAction()
		{
			if($this->getRequest()->isXmlHttpRequest())
			{
				if(false == Cmd::isManager($this->_classId)) exit();  // 不是管理员
				$apply_id = $this->getRequest()->getPost('apply_id');
				$db = Zend_Registry::get('dbClass');
				$row = $db->fetchRow('SELECT `class_apply_content` FROM `vi_class_apply` WHERE `class_apply_id` = ?', $apply_id);
				echo stripslashes($row['class_apply_content']);
			}
		}
		
		# 班级申请加入列表
		function classapplylistAction()
		{
			if($this->getRequest()->isXmlHttpRequest())
			{
				if(false == Cmd::isManager($this->_classId)) exit();  // 不是管理员
				$this->_sessClass->default['managerMember'] = "applyList({$this->_classId})";
				$db = Zend_Registry::get('dbClass');
				$rows = $db->fetchAll('SELECT * FROM `vi_class_apply` WHERE `class_id` = ?', $this->_classId);
				$this->view->class_id = $this->_classId;
				$this->view->applies = $rows;
				$this->render('apply-list');
			}
		}
		
		# 加入班级连接反馈
		function joinAction()
		{
			if($this->getRequest()->isXmlHttpRequest())
			{
				// 判断是否已经是该班级成员
				if(false != MemberModel::isJoined($this->_classId, $this->view->passport('uid')))
				{
					$this->render('join-already');
				}
				elseif (false != ApplyModel::isApplied($this->_classId, $this->view->passport('uid')))
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
		
		# 申请加入班级表单
		function joinapplyAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				$class_apply_content = Commons::html2str(strip_tags(trim($request->getPost('apply_content'))));
				
				// 判断是否已经是该班级成员
				if(false != MemberModel::isJoined($this->_classId, $this->view->passport('uid')))
				{
					echo '您已经是班级成员';
				}
				elseif (false != ApplyModel::isApplied($this->_classId, $this->view->passport('uid')))
				{
					echo '您已经提交了加入申请';
				}
				else // 写入数据库
				{
					$data = array(
						'class_id' => $this->_classId,
						'class_member_id' => $this->view->passport('uid'),
						'class_apply_content' => $class_apply_content,
						'class_apply_time' => time()
					);
					if(false != ApplyModel::insert($data))
					{
						echo '成功完成申请！请等待审核';
					}
				}
			}
		}
		
		# 注册时候直接申请
		function joinapplydirectAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{	
				$uid = $request->getParam('uid');
				$name = $request->getParam('realName');
				$sex = $request->getParam('sex');
				$class_ids = explode(',', $this->getRequest()->getPost('class_id'));
				DbModel::userInit(array(
					'uid' => $uid,
					'realName' => $name,
					'sex' => $sex
				));
				foreach ($class_ids as $class_id)
				{
					if(false == MemberModel::isJoined($class_id, $uid) && false == ApplyModel::isApplied($class_id, $uid))
					{
						$data = array(
						'class_id' => $class_id,
						'class_member_id' => $uid,
						'class_apply_content' => '我是'.$name.',前来报道~',
						'class_apply_time' => time()
						);
						ApplyModel::insert($data);
					}
				}
			}
		}
		
		/* 班级公告相关 //////////////////////////////////////////////////////// */
		
		# 班级公告修改动作
		function classnoticemodAction()
		{
			$request = $this->getRequest();
			if($request->isXmlHttpRequest())
			{
				$class_notice = Commons::html2str(strip_tags(trim($request->getPost('notice_content'))));
				if(false == Cmd::isManager($this->_classId)) exit();  // 不是管理员
				$db = Zend_Registry::get('dbClass');
				if($db->update('tbl_class',
								array('class_notice'=>$class_notice),
								'class_id = '.$this->_classId))
				$this->view->content = $class_notice;
				$this->view->class_id = $this->_classId;
				$this->render('class-notice');
			}
		}
		
		# 编辑班级公告获取
		function classnoticefetchAction()
		{
			if($this->getRequest()->isXmlHttpRequest())
			{
				if(false == Cmd::isManager($this->_classId)) exit();  // 不是管理员
				$db = Zend_Registry::get('dbClass');
				$row = $db->fetchRow('SELECT `class_notice` FROM `tbl_class` 
							   		  WHERE `class_id` = ?',$this->_classId);
				$this->view->content =strip_tags($row['class_notice']);
				$this->view->class_id = $this->_classId;
				$this->render('class-notice');
			}
		}
	}