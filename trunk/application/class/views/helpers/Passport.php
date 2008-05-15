<?php

	class Zend_View_Helper_Passport
	{
		function passport($index = false)
		{
			if(false != $index) return Zend_Registry::get('sessCommon')->login[$index];
			else return Zend_Registry::get('sessCommon')->login;
		}
	}

?>