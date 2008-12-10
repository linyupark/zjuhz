<?php

	class ApiController extends Zend_Controller_Action
	{
		function init()
		{
			Zend_Layout::getMvcInstance()->disableLayout();
  			Zend_Controller_Action_HelperBroker::removeHelper('viewRenderer');
			$this->getResponse()->insert('nav','');
		}
		
		function indexAction()
		{
			$jsoncallback = $this->_getParam('jsoncallback');
			$sess = Zend_Registry::get('sessCommon')->login;
			if($sess['uid'])
			{
				$sess['result'] = 'success';
				echo $jsoncallback.'('.Zend_Json::encode($sess).')';
			}
			else
			echo $jsoncallback.'('.Zend_Json::encode(array('result'=>'failed')).')';
		}
	}

?>