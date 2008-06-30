<?php

/* 群组标签 */

class TagController extends Zend_Controller_Action
{
    function init()
    {}
    
    public function indexAction()
    {
        
    }
    
    # 增加标签临时用
    public function addAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $sort = $this->_getParam('sort');
        $tags = $this->_getParam('tags');
        if($this->_getParam('psw') == '123')
        {
            GroupTagModel::insert($sort, urldecode($tags));
            echo "done!";
        }
    }
}

?>