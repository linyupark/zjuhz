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
        
    }
}

?>