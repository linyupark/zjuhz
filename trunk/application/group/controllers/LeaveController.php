<?php

/**
 * 退出群组操作
 **/

class LeaveController extends Zend_Controller_Action
{
    function init()
    {
    }
    
    function indexAction()
    {
        $gid = $this->_getParam('gid');
        $myid = Cmd::myid();
        // 删除member中的相关记录
        GroupMemberModel::kickout($myid, $gid);
        Cmd::flushGroupSession();
        echo 'done';
    }
}

?>