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
        switch($state)
        {
            case 1: echo '当前您的群组状态为离开'; break;
            case 2: echo '当前您的群组状态为隐身'; break;
            default: echo '当前您的群组状态为在线'; break;
        }
    }
    
    public function indexAction()
    {
        $request = $this->getRequest();
        // 更新群组信息
        if($request->getParam('do') == 'refresh')
        {
            Cmd::flushGroupSession();
            $this->_redirect('/group/my');
        }
    }
}

?>