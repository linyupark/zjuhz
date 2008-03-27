<?php

class IndexController extends Zend_Controller_Action
{
    function indexAction()
    {
    	$flag = $this->getRequest()->getParam('f');
    	if($flag == 'in')
    	$this->render('index2');
    }
}
