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
            $this->view->cache = CacheModel::init(null, 600);
            $this->view->info_xmlrpc = new Zend_XmlRpc_Client('http://xmlrpc/InfoServer.php');
        }
    }