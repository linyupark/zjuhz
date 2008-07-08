<?php

/**
 * 邀请加入群组 InviteController
 * 
 * @author zjuhz.com
 * @version 
 */

class InviteController extends Zend_Controller_Action
{
	function init()
    {
        $this->view->gid = $this->getRequest()->getParam('gid');
        $this->view->uid = Zend_Registry::get('sessCommon')->login['uid'];
		$this->view->controller_name = $this->getRequest()->getControllerName();
        $this->view->action_name = $this->getRequest()->getActionName();
        if(!GroupMemberModel::role($this->view->uid, $this->view->gid))
        {
            $this->_redirect('/');
        }
        else // 获取群组的详细信息
        {
            $this->view->groupInfo = GroupModel::info($this->view->gid);
        }
    }
	
	# 搜索校友
	public function searchAction()
	{
		if($this->getRequest()->isPost()) // 处理搜索提交
		{
			$R = $this->getRequest();
			// 一些可匹配的属性
			$name = $R->getPost('name');
			$sex = $R->getPost('sex');
			$college = $R->getPost('college');
			$year = $R->getPost('year');
			$city = $R->getPost('city');
			$temp = $name.$sex.$college.$year.$city;
			if(empty($temp))
			echo '<div class="error">请最少填写一项，否则不会执行搜索操作</div>';
            else // 进行匹配搜索
            {
                // 获取匹配总数
                $db = Zend_Registry::get('dbGroup');
                $select = $db->select()->from('tbl_group_user');
                if($name) $select->where('realName = ?', $name);
                if($sex) $select->where('sex = ?', $sex);
                if($college) $select->where('college = ?', $college);
                if($year) $select->where('year = ?', $year);
                if($city) $select->where('location_c = ?', $city);
                $rows = $db->query($select)->fetchAll();
                $num_rows = count($rows);
                if($num_rows > 0) //找到数据则进行罗列显示
                {
                    $this->view = $this->_helper->viewRenderer->view;
                    $this->view->gid = $this->getRequest()->getParam('gid');
                    // 目前已经在群组内的uid,做排除(1,2,3,4,....)
                    $uid_str = '';
                    $uids = GroupMemberModel::fetchIds($this->view->gid);
                    foreach($uids as $k => $v)
                    {
                        if($k == 0) $uid_str = $v['user_id'];
                        else $uid_str .= ','.$v['user_id'];
                    }
                    $page = $this->_getParam('p', 1);
                    Page::$pagesize = 9;
                    Page::create(array(
                        'href_open' => '<a href="javascript:void(0)" onclick="return doSearch(%d)">',
                        'href_close' => '</a>',
                        'num_rows' => $num_rows,
                        'cur_page' => $page
                    ));
                    $select->limit(Page::$pagesize, Page::$offset);
                    //$this->view->sql = $select->__toString();
                    $rows = $db->query($select)->fetchAll();
                    // 分配数据
                    $this->view->members = $uid_str;
                    $this->view->pagination = Page::$page_str;
                    $this->view->page = $page;
                    $this->view->rows = $rows;
                    $this->view->numrows = $num_rows;
                    $this->render('search-result');
                }
                else
                {
                    echo '<div class="notice txtc">没有找到相匹配的校友</div>';
                }
            }
		}
		else // 显示搜索表单
		{
			$this->view->college = Zend_Registry::get('iniConfig')->college->name->toArray();
		}
	}
	
    # 请求加入列表
    public function applyAction()
    {
        if(!Cmd::isManager($this->view->gid)) $this->_redirect('/');
        
        if($this->_getParam('do') == 'del')
        {
            $request = $this->getRequest();
            $uid = $request->getPost('uid');
            $gid = $request->getPost('gid');
            GroupModel::delApply($uid, $gid);
        }
        
        $apply_str = GroupModel::info($this->view->gid, 'apply');
        if($apply_str == null) $this->view->applies = false;
        else $this->view->applies = explode(',', $apply_str);
    }
    
    # 邀请操作
    public function doAction()
    {
        $request = $this->getRequest();
        $uid = $request->getPost('uid');
        $group_id = $request->getPost('gid');
        
        if(UserModel::isInvited($group_id, $uid))
        {
            echo '<div class="notice">'.UserModel::fetch($uid,'realName').'已经邀请过了~</div>';
        }
        else
        {
            UserModel::invite($group_id, $uid);
            echo '<div class="success">成功邀请'.UserModel::fetch($uid,'realName').'</div>';
        }
    }
    
	# 没被认领的校友
	public function indexAction()
	{
		$this->view->page = $this->_getParam('p', 1);
        $pagesize = 8;
        Page::$pagesize = $pagesize;
		$result = UserModel::vag($pagesize, $this->view->page);
        Page::create(array(
            'href_open' => '<a href="/group/invite?gid='.$this->view->gid.'&p=%d">',
            'href_close' => '</a>',
            'num_rows' => $result['numrows'],
            'cur_page' => $this->view->page
        ));
        if($pagesize > $result['numrows']) $pagesize = $result['numrows'];
        $this->view->vogs = $result['rows'];
        $this->view->pagination = Page::$page_str;
        $this->view->vog_num = $result['numrows'];
        $this->view->offset = Page::$offset+1;
        $this->view->pagesize = $pagesize;
	}

}
