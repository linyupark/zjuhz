<?php

/* 我的群组 */

class MyController extends Zend_Controller_Action
{
    function init()
    {
        if(Zend_Registry::get('sessGroup')->my == null)
        Cmd::flushGroupSession();
    }
    
    # 我的话题管理列表
    public function topicsAction()
    {
        $type = $this->_getParam('type', 'create'); // 默认为自己创建的帖子
        $page = $this->_getParam('p', 1);
        
        $pagesize = Page::$pagesize = 30;
        
        // 计算创建帖子总数和回复总数
        $reply_num = GroupReplyModel::count(Cmd::myid());
        $topic_num = GroupTopicModel::count(Cmd::myid());
        
        $db = Zend_Registry::get('dbGroup');
        
        if($type == 'create') // 罗列我发表的所有帖子
        {
            Page::create(array(
                'href_open' => '<a href="/group/my/topics?type='.$type.'&p=%d">',
                'href_close' => '</a>',
                'num_rows' => $topic_num,
                'cur_page' => $page
            ));
            
            $rows = $db->fetchAll('SELECT `topic_id`,`group_id`,`title`,`reply_num`,`reply_time`
                                    FROM `tbl_group_topic`
                                    WHERE `pub_user` = '.Cmd::myid().'
                                    ORDER BY `pub_time` DESC 
                                    LIMIT '.Page::$offset.','.$pagesize);
        }
        
        if($type == 'join') // 罗列我参与的所有帖子
        {
            Page::create(array(
                'href_open' => '<a href="/group/my/topics?type='.$type.'&p=%d">',
                'href_close' => '</a>',
                'num_rows' => $reply_num,
                'cur_page' => $page
            ));
            
            $rows = $db->fetchAll('SELECT DISTINCT `tbl_group_reply`.`topic_id`,
                                             `tbl_group_topic`.`group_id`,
                                             `tbl_group_topic`.`title`,
                                             `tbl_group_topic`.`reply_num`,
                                             `tbl_group_topic`.`reply_time` 
                                    FROM `tbl_group_reply`
                                    LEFT JOIN `tbl_group_topic` ON `tbl_group_reply`.`topic_id` = `tbl_group_topic`.`topic_id`
                                    WHERE `tbl_group_reply`.`user_id` = '.Cmd::myid().'
                                    ORDER BY `tbl_group_reply`.`reply_time` DESC 
                                    LIMIT '.Page::$offset.','.$pagesize);
        }
        
        $this->view->rows = $rows;
        $this->view->pagination = Page::$page_str;
        $this->view->topic_num = $topic_num;
        $this->view->reply_num = $reply_num;
        $this->view->type = $type;
    }
    
    # 自己的好友列表
    public function friendsAction()
    {
        $friends = UserModel::fetch(Cmd::myid(), 'friends');
        if($friends != null)
        $this->view->friends = explode(',', $friends);
    }
    
    # 自己的群组资料(个人补充信息,隐私设置)
    public function profileAction()
    {
        if($this->_getParam('do') == 'save') // 保存资料
        {
            $R = $this->getRequest();
            $V = new Lp_Valid();
            $phone = $V->of($R->getPost('phone'), 'phone', '手机号码', 'trim|strip_tags|numeric|str_exact[11]');
            $email = $V->of($R->getPost('email'), 'email', '电子邮箱', 'trim|valid_email');
            $im = $V->of($R->getPost('im'), 'im', '即时通讯', 'trim|strip_tags');
            $job = $V->of($R->getPost('job'), 'job', '从事职业', 'trim|strip_tags');
            $intro = $V->of($R->getPost('intro'), 'intro', '个人介绍', 'trim|strip_tags|nl2br');
            $interest = $V->of($R->getPost('interest'), 'interest', '兴趣爱好', 'trim|strip_tags|nl2br');
            $sign = $V->of($R->getPost('sign'), 'sign', '个性签名', 'trim|strip_tags|nl2br');
            
            if(!empty($phone) && $V->getMessage('phone'))
            {
                echo '<div class="error">'.$V->getMessage('phone').'</div>';
            }
            elseif(!empty($email) && $V->getMessage('email'))
            {
                echo '<div class="error">'.$V->getMessage('email').'</div>';
            }
            else // 进行数据更新
            {
                $data = array(
                    'ext_phone' => $phone,
                    'ext_email' => $email,
                    'ext_im' => $im,
                    'ext_job' => $job,
                    'ext_intro' => $intro,
                    'ext_interest' => $interest,
                    'ext_sign' => $sign,
                    'ext_private' => $R->getPost('private')
                );
                UserModel::update($data, Cmd::myid());
                echo '<div class="success">群组资料保存成功!</div>';
            }
        }
        else // 显示资料
        {
            
        }
    }
    
    # 状态调整
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
            $this->_redirect('/group/my');
        }
    }
}

?>