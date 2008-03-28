<?php

class IndexController extends Zend_Controller_Action
{
	function init()
	{
		//$this->view->headTitle('haha');
		$this->view->headScript()->appendFile('/static/scripts/jquery.js')
								 ->appendFile('/static/scripts/temp.js');
		$this->view->headLink()->appendStylesheet('/static/styles/temp.css','screen');
	}
	
    function indexAction()
    {
    	//$this->render('front_header', null, true);
		//$this->render('index');
		//$this->render('front_footer', null, true);
		$layout = Zend_Layout::getMvcInstance();
		$layout->setLayout('main');
		

		//$layout->content = $this->render('index');
		//$layout->sidebar = $this->render('nav');
		
		//$layout->sidebar = 'bar2';
    }
    
    function navAction()
    {

    }
}
