<?php

/* 群组管理 */

class ManageController extends Zend_Controller_Action
{
    function init()
    {
        $this->view->gid = $this->getRequest()->getParam('gid');
        // 非管理员权限直接跳转
        if(!GroupMemberModel::isManager($this->view->passport('uid'), $this->view->gid))
        {
            $this->_redirect('/');
        }
        else // 获取群组的详细信息
        {
            $this->view->groupInfo = GroupModel::info($this->view->gid);
        }
    }
    
    public function indexAction()
    {
        $this->view->action_name = $this->getRequest()->getActionName();
        $this->view->sorts = Zend_Registry::get('iniGroup')->sort->name->toArray();
		$this->view->college = Zend_Registry::get('iniConfig')->college->name->toArray();
		$this->view->profession = Zend_Registry::get('iniGroup')->profession->name->toArray();
		$this->view->job = Zend_Registry::get('iniGroup')->job->name->toArray();
    }
}

?>