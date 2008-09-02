<?php

    class SubjectController extends Zend_Controller_Action
    {
        function init()
        {
            $this->getResponse()->insert('nav','');
            $this->_helper->layout->disableLayout();
        }
        
        # 成立大会
        function foundAction()
        {
            $root = $_SERVER['DOCUMENT_ROOT'];
            
            $db = file_get_contents($root.'/static/foundb.txt');
            if($db != '') // 空的数据
            {
                $list = unserialize($db);
                if(@array_key_exists(Cmd::myid(), $list))
                $this->view->signup = true;
            }
            else $list = null; // 默认名单为空
            
            $R = $this->getRequest();
            
            // 查看已经报名人员
            if($this->_getParam('list') == 'view')
            {
                $this->view->listview = $list;
            }
            
            // 报名提交
            if($R->isPost())
            {
                $V = new Lp_Valid();
                $name = $V->of($R->getPost('name'), 'name', '姓名', 'trim|strip_tags|required');
                $email = $V->of($R->getPost('email'), 'email', '邮箱', 'trim|valid_email');
                $mobile = $V->of($R->getPost('mobile'), 'mobile', '手机', 'trim|numeric');
                $uid = (int)$R->getPost('uid');
                
                if($V->getMessages() != false)
                {
                    echo '<br /><div class="error">'.$V->getMessages('* ').'</div>';
                }
                else
                {
                    $db = file_get_contents($root.'/static/foundb.txt');
                    if($db == '') // 空的数据
                    {
                        $list = array();
                        $list[$uid] = array(
                            'name' => $name,
                            'email' => $email,
                            'mobile' => $mobile
                        );
                        file_put_contents($root.'/static/foundb.txt', serialize($list));
                        echo 'success';
                    }
                    else // 补充数据
                    {
                        $list = unserialize($db);
                        if(!@array_key_exists($uid, $list))
                        {
                            // 写入数据
                            $list[$uid] = array(
                                'name' => $name,
                                'email' => $email,
                                'mobile' => $mobile
                            );
                            // 保存进txt
                            file_put_contents($root.'/static/foundb.txt', serialize($list));
                            echo 'success';
                        }
                        else // 已经报名过了
                        {
                            echo '<br /><div class="notice">您已经成功报名</div>';
                        }
                    }
                }
            }
            $this->view->uname = $_COOKIE['zjuhz_member']['uname']; // 记住账号
            $this->view->pswd = Commons::decrypt($_COOKIE['zjuhz_member']['pswd']); // 记住账号
        }
        
        #2008毕业校友
        function zju2008Action()
        {
            $this->view->cache = CacheModel::init(null, 600);
            $this->view->info_xmlrpc = new Zend_XmlRpc_Client('http://xmlrpc/InfoServer.php');
        }
    }