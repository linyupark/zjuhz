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
	
    # 邀请操作
    public function doAction()
    {
        $request = $this->getRequest();
        $uid = $request->getPost('uid');
        $group_id = $request->getPost('gid');
        if(UserModel::isInvited($group_id, $uid))
        {
            $this->_helper->layout->setLayout('error');
            echo '<div class="notice">'.UserModel::fetch($uid,'realName').'已经邀请过了~</div>';
        }
        else
        {
            UserModel::invite($group_id, $uid);
            $this->_helper->layout->setLayout('success');
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
