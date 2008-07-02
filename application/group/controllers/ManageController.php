<?php

/* 群组管理 */

class ManageController extends Zend_Controller_Action
{
    function init()
    {
        $this->view->gid = $this->getRequest()->getParam('gid');
        $this->view->uid = Zend_Registry::get('sessCommon')->login['uid'];
        $this->view->controller_name = $this->getRequest()->getControllerName();
        $this->view->action_name = $this->getRequest()->getActionName();
        // 非管理员权限直接跳转
        // 是否是管理员
        if(false == Cmd::isManager($this->view->gid))
        {
            $this->_redirect('/');
        }
        else // 获取群组的详细信息
        {
            $this->view->groupInfo = GroupModel::info($this->view->gid);
        }
    }
    
    /**
     * 修改群组角色
     *
     */
    public function roleAction()
    {
    	if(!Cmd::isCreater($this->view->gid))
    	{
            $this->_helper->layout->setLayout('error');
            echo '<ul class="error"><li>只有群组创建者才能修改角色名称。</li></ul>';
        }
    	else
    	{
    		$request = $this->getRequest();
    		if($request->isPost()) // 修改
    		{
    			$V = new Lp_Valid();
                $creater = $V->of($request->getPost('creater'), 'creater', '创建人', 'trim|strip_tags|str_between[2,6]');
                $manager = $V->of($request->getPost('manager'), 'creater', '管理人', 'trim|strip_tags|str_between[2,6]');
                $member = $V->of($request->getPost('member'), 'creater', '成员', 'trim|strip_tags|str_between[2,6]');
                if($V->getMessages())
                {
                    $this->_helper->layout->setLayout('error');
                    echo '<ul class="error">'.$V->getMessages('<li>','</li>').'</ul>';
                }
                else
                {
                    $data = array(
                        'creater' => $creater,
                        'manager' => $manager,
                        'member' => $member
                    );
                    GroupRoleModel::update($data, $this->view->gid);
                    echo Commons::js_jump('/group/manage/member?gid='.$this->view->gid, 0);
                }
    		}
    	}
    }
    
    /**
     * 修改群组论坛主题属性
     *
     */
    public function topicAction()
    {
    	$type = $this->_getParam('type');
    	$topic_arr = $this->_getParam('t', null);
    	if($topic_arr == null) return ;
    	switch ($type)
    	{
    		case 'top': //置顶
    			foreach ($topic_arr as $topic_id)
    			{
    				GroupTopicModel::update(array(
    					'is_top' => 1
    				), $topic_id);
    			}
    			echo '成功置顶，<a href="javascript:history.go(0)">刷新查看</a>';
    			break;
    		
    		case 'no_top': // 取消置顶
    			foreach ($topic_arr as $topic_id)
    			{
    				GroupTopicModel::update(array(
    					'is_top' => 0
    				), $topic_id);
    			}
    			echo '成功取消置顶，<a href="javascript:history.go(0)">刷新查看</a>';
    			
    		case 'elite': //加精
    			foreach ($topic_arr as $topic_id)
    			{
    				GroupTopicModel::update(array(
    					'is_elite' => 1
    				), $topic_id);
    			}
    			echo '成功加精，<a href="javascript:history.go(0)">刷新查看</a>';
    			break;
    		
    		case 'no_elite': // 取消加精
    			foreach ($topic_arr as $topic_id)
    			{
    				GroupTopicModel::update(array(
    					'is_elite' => 0
    				), $topic_id);
    			}
    			echo '成功取消加精，<a href="javascript:history.go(0)">刷新查看</a>';
    			
    		case 'delete': // 删除
    			foreach ($topic_arr as $topic_id)
    			{
    				GroupTopicModel::delete($this->view->gid, $topic_id);
    			}
    			echo '成功取消加精，<a href="javascript:history.go(0)">刷新查看</a>';
    		default:
    			break;
    	}
    }
    
    # 群组公告
    public function noticeAction()
    {
        $content = $this->getRequest()->getPost('content');
        GroupModel::update(array('notice'=>$content), $this->view->gid);
    }
    
    # 群组类型 ---------------------------------
    public function privateAction()
    {
        $private = $this->_getParam('private', false);
        if(false != $private)
        {
            GroupModel::update(array(
                'private' => (int)$private
            ), $this->view->gid);
            $this->_helper->layout->setLayout('success');
			echo '<div class="success span-13">群组类型修改成功!</div>';
        }
        else // 显示表单
        {
            
        }
    }
    
    /**
     * 成员管理
     * */
    public function memberAction()
    {
        $type = $this->_getParam('type', 'list');
        $page = $this->_getParam('p', 1);
        $member_id = $this->_getParam('uid');
        if($type == 'list')
        {
            // 罗列成员列表
            Page::$pagesize = 10;
            $result = GroupMemberModel::fetchAll($this->view->gid, Page::$pagesize, $page);
            Page::create(array(
                'href_open' => '<a href="/group/manage/member?gid='.$this->view->gid.'&type=list&p=%d">',
                'href_close' => '</a>',
                'num_rows' => $result['numrows'],
                'cur_page' => $page
            ));
            $this->view->members = $result['rows'];
            $this->view->pagination = Page::$page_str;
            $this->render('member-list');
        }
        if($type == 'downgrade')
        {
            // 革职
            GroupMemberModel::update($member_id, array('role'=>'member'), $this->view->gid);
            echo '<script>$("#member_'.$member_id.' a[href*=downgrade]").text("成功革职")</script>';
        }
        if($type == 'upgrade')
        {
            // 提拔
            GroupMemberModel::update($member_id, array('role'=>'manager'), $this->view->gid);
            echo '<script>$("#member_'.$member_id.' a[href*=upgrade]").text("成功提拔")</script>';
        }
        if($type == 'kickout')
        {
            // 踢出
            GroupMemberModel::kickout($member_id, $this->view->gid);
            echo '<script>$("#member_'.$member_id.' a[href*=kickout]").text("成功踢出")</script>';
        }
    }
    
    public function indexAction()
    {
        if(GroupMemberModel::role($this->view->uid, $this->view->gid) != 'creater')
        {
            $this->render('nopower');
        }
        else
        {
            $this->view->sorts = Zend_Registry::get('iniGroup')->sort->name->toArray();
            $this->view->college = Zend_Registry::get('iniConfig')->college->name->toArray();
            $this->view->profession = Zend_Registry::get('iniGroup')->profession->name->toArray();
            $this->view->job = Zend_Registry::get('iniGroup')->job->name->toArray();
        }
    }
    
    /**
     * 修改群组常规信息
     * */
    public function modbaseinfoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
		$request = $this->getRequest();
		$valid = new Lp_Valid();
		$user_id = $this->view->uid;
		$name = $valid->of($request->getPost('name'), 'name', '群组名称', 'trim|strip_tags|required');
		$intro = $valid->of($request->getPost('intro'), 'intro', '群组介绍', 'trim|strip_tags|str_between[12,200]');
		$sort = $valid->of($request->getPost('sort'), 'sort', '群组分类', 'required');
		$province = null;$city = null;
		$profession = null;
		$company = null;
		$occupation = null;
		$year = null;
		$college = null;
		if($sort == '11') //同事会
		{
			$province = $valid->of($request->getPost('corp_province'), 'province', '企业所在省', 'required');
			$city = $valid->of($request->getPost('corp_city'), 'city', '企业所在市', 'required');
			$profession = $valid->of($request->getPost('corp_profession'), 'profession', '企业所属行业', 'required');
			$company = $valid->of($request->getPost('company'), 'company', '企业名称', 'trim|strip_tags|required');
		}
		if($sort == '12') //同行会
		{
			$province = $valid->of($request->getPost('job_province'), 'province', '工作所在省', 'required');
			$city = $valid->of($request->getPost('job_city'), 'city', '工作所在市', 'required');
			$profession = $valid->of($request->getPost('job_profession'), 'profession', '工作所属行业', 'required');
			$occupation = $valid->of($request->getPost('job_occupation'), 'occupation', '职业名称', 'required');
		}
		if($sort == '13') //同学会
		{
			$year = $valid->of($request->getPost('class_year'), 'year', '入学年份', 'required');
			$college = $valid->of($request->getPost('class_college'), 'college', '所属学院', 'required');
		}
		$tags = $valid->of($request->getPost('tags'), 'tags', '群组标签', 'trim|strip_tags|required');
		// 校验失败
		if(false != $valid->getMessages())
		{
			$this->_helper->layout->setLayout('error');
			echo '<ul class="error span-13">'.$valid->getMessages('<li>','</li>').'</ul>';
		}
        // 名称校验
        else
        {
            if(false != GroupModel::nameExistMod($name, $this->view->gid))
			{
				$this->_helper->layout->setLayout('error');
				echo '<ul class="error span-13"><li>群组名称 '.$name.'已经被占用,请重新选择~</li></ul>';
			}
            else
            {
                $location = null;
				if($province != null && $city != null)
				{
					$location = $province.','.$city;
				}
                $data = array(
					'sort_id' => $sort,
					'name' => $name,
					'intro' => $intro,
					'tags' => $tags,
					'ext_location' => $location,
					'ext_trade' => $profession,
					'ext_year' => $year,
					'ext_college' => $college,
					'ext_job' => $occupation,
					'ext_corp' => $company
				);
                GroupModel::update($data, $this->view->gid);
                if($this->view->groupInfo['tags'] != $tags)
                GroupTagModel::insert($sort, $tags);
                Cmd::flushGroupSession();
                // 显示成功信息
				$this->_helper->layout->setLayout('success');
				echo '<div class="success span-13">群组"'.$name.'"修改成功!</div>';
            }
        }
    }
}

?>