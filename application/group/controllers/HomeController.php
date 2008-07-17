<?php

/* 群组内部首页 */

class HomeController extends Zend_Controller_Action
{
    function init()
    {
        $this->view->gid = $this->getRequest()->getParam('gid');
        $this->view->uid = Zend_Registry::get('sessCommon')->login['uid'];
        $this->view->role = GroupMemberModel::role($this->view->uid, $this->view->gid);
		$this->view->controller_name = $this->getRequest()->getControllerName();
        $this->view->action_name = $this->getRequest()->getActionName();
        $this->view->groupInfo = GroupModel::info($this->view->gid);
    }
	
	public function nameAction()
	{
		$name = $this->_getParam('of');
		$gid = GroupModel::url2gid($name);
		$this->_helper->layout->disableLayout(true);
		$this->_helper->viewRenderer->setRender(true);
		$this->_forward('index',null,null,array('gid'=>$gid));
	}
    
    public function indexAction()
    {
    	// 如果是用户成员则记录最后登陆该群组的时间
    	if(!Cmd::isGuest($this->view->gid))
    	{
    		GroupMemberModel::update($this->view->uid, array('last_access'=>time()), $this->view->gid);
    	}
    	// 将用户最后所到群组id放入群组表
    	$comefrom = UserModel::fetch($this->view->uid, 'come_from');
        if($comefrom != null)
    	GroupModel::associate($comefrom, $this->view->gid);
    	// 更新用户最后所到的群组信息
    	UserModel::comefrom($this->view->uid, $this->view->gid);
        $this->view->elite = $this->_getParam('elite');
    }
}

?>