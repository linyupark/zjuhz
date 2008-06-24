<?php

/* 我的群组 */

class MyController extends Zend_Controller_Action
{
    function init()
    {
        if(Zend_Registry::get('sessGroup')->my == null)
        Cmd::flushGroupSession();
    }
    
    public function stateAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $state = $this->getRequest()->getPost('val');
        UserModel::stateMod($this->view->passport('uid'), $state);
        Zend_Registry::get('sessGroup')->state = $state;
        switch($state)
        {
            case 1: echo '当前您的群组状态为离开'; break;
            case 2: echo '当前您的群组状态为隐身'; break;
            default: echo '当前您的群组状态为在线'; break;
        }
    }
    
    # 我的邀请函
    public function inviteAction()
    {
        if($this->getRequest()->isPost())
        {
            $this->_helper->viewRenderer->setNoRender(true);
            $gid = $this->getRequest()->getPost('gid');
            $uid = $this->view->passport('uid');
            if($this->_getParam('do') == 'reject') // 拒绝邀请
            {
                if(true == UserModel::delInvite($uid, $gid))
                echo '成功拒绝';
            }
            if($this->_getParam('do') == 'accept') // 接受邀请
            {
                if(true == UserModel::delInvite($uid, $gid))
                {
                    $data = array(
                        'group_id' => $gid,
                        'user_id' => $uid,
                        'join_time' => time(),
                        'last_access' => time(),
                        'role' => 'member'
                    );
                    GroupMemberModel::join($uid, $gid, $data);
                    GroupModel::update(array('member_num'=>new Zend_Db_Expr('member_num+1')), $gid);
                    UserModel::haveGroup($uid);
                    Cmd::flushGroupSession();
                    echo '成功加入';
                }
            }
        }
        else
        {
            $invites = UserModel::fetch($this->view->passport('uid'), 'group_invite');
            if($invites != null)
            $this->view->invites = explode(',', $invites);
        }
    }
    
    # 我的群组首页
    public function indexAction()
    {
        $request = $this->getRequest();
        $this->view->state = Zend_Registry::get('sessGroup')->state;
        // 更新群组信息
        if($request->getParam('do') == 'refresh')
        {
            Cmd::flushGroupSession();
            $this->_redirect('/group/my');
        }
    }
}

?>