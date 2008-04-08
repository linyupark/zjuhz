<?php 

	/**
	 * 负责INPUT输入的过滤和校验
	 *
	 */
	class InputChains
	{
		private $_messages = array();
		private $_valid;
		
		function __construct()
		{
			$this->_valid = new Zend_Validate();
		}
		
		# 信息内容 -----------------------------------------------------
		function entityContent($input, $name=null)
		{
			$input = trim($input);
			if(empty($input))
			$this->_messages[$name] = '信息内容不能为空';
			return $input;
		}
		
		# 信息标题 -----------------------------------------------------
		function entityTitle($input, $name=null)
		{
			$input = trim(strip_tags($input));
			
			$valid = $this->_valid;
			$valid->addValidator(new Zend_Validate_StringLength(3,100));
			if(!$valid->isValid($input))
			$this->_messages[$name] = '信息标题不能为空且长度不得少于3字符大于100字符';
			return $input;
		}
		
		# 信息日期 -----------------------------------------------------
		function entityDate($input, $name=null)
		{
			$input = trim($input);
			
			if(!strtotime($input))
			$this->_messages[$name] = '信息时间格式错误';
			return strtotime($input);
		}
		
		# 信息标签 -----------------------------------------------------
		function entityTag($input, $name=null)
		{
			$input = implode(',',$input);
			$input = trim(strip_tags($input));
			
			return $input;
		}
		
		
		# 获取校验信息
		function getMessages()
		{
			return $this->_messages;
		}
	}