<?php

    class SubjectController extends Zend_Controller_Action
    {
        function init()
        {
            $this->getResponse()->insert('nav','');
            $this->_helper->layout->disableLayout();
        }
        
        function zju2008Action()
        {
            
        }
    }