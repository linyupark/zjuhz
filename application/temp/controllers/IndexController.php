<?php

class IndexController extends Zend_Controller_Action
{
    function indexAction()
    {
    	$this->render('front_header', null, true);
		$this->render('index');
		$this->render('front_footer', null, true);
    }
}
