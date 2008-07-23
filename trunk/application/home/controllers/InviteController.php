<?php

    /**
     * 扩展弹窗邀请
     * */
    
    class InviteController extends Zend_Controller_Action
    {
        function init()
        {
            $this->User = new User();
        }
        
        function testAction()
        {
            $R = $this->getRequest();
            $cname = $R->getPost('cid');
            echo $cname;
        }
        
        function cardAction()
        {
            $R = $this->getRequest();
            $cname = $R->getParam('cname');
            
            $card = $this->User->findCardByName($cname);
            $group = $this->User->findGroupByName('临时邀请组');

            if($card == false) // 建立新名片
            {
                if($group == false) // 建立临时邀请通讯组
                {
                    $gid = Commons::getRandomStr(Cmd::myid(), 5);
                    $this->User->createGroup(array(
                        'uid' => Cmd::myid(),
                        'gname' => '临时邀请组',
                        'gid' => $gid,
                        'lastModi' => $_SERVER['REQUEST_TIME']
                    ));
                }
                else $gid = $group['gid'];
                
                $cid = Commons::getRandomStr(Cmd::myid(), 10);
                echo Zend_Json::encode(array('new'=>true,'gid'=>$gid,'cid'=>$cid));
            }
            
            else // 直接生成邀请
            {
                if($card['status'] == 2)
                echo Zend_Json::encode(array('success'=>true));
                else
                echo Zend_Json::encode(array('new'=>false,'cid'=>$card['cid']));
            }
        }
        
        function formAction()
        {
            $this->view = $this->getHelper('viewRenderer')->view;
            $this->render('form');
        }
    }