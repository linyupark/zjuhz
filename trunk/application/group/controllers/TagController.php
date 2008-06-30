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
        if($this->_getParam('psw') == '123' && $this->_getParam('sort') && $this->_getParam('tags'))
        {
            GroupTagModel::insert($sort, $tags);
            echo "done!";
        }
    }
}

?>