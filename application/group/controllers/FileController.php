<?php

/* 群组文件空间 */

class FileController extends Zend_Controller_Action
{
    function init()
    {
        $this->getResponse()->insert('nav','');
    }
    
    public function uploadAction()
    {
        $this->_helper->viewRenderer->setNoRender(false);
        $this->view->gid = $this->_getParam('gid');
    }
}

?>