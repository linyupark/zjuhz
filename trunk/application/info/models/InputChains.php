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
		
		# 管理密码 -------------------------------------------------
		function managerPassword($input,$input2, $name='manager_password')
		{
			$input = trim($input);
			$input2 = trim($input2);
			if(empty($input) || $input != $input2)
			$this->_messages[$name] = '密码不能为空且两次输入密码要一致';
			return md5($input);
		}
		
		# 管理成员名称 -------------------------------------------------
		function managerName($input, $name='manager_name')
		{
			$input = trim($input);
			$valid = $this->_valid;
			$valid->addValidator(new Zend_Validate_StringLength(3,50));
			if(!$valid->isValid($input))
			$this->_messages[$name] = '帐号长度范围(3~50)';
			return $input;
		}
		
		# 分类名称 -----------------------------------------------------
		function categoryName($input, $name='category_name')
		{
			$input = trim(strip_tags($input));
			$valid = $this->_valid;
			$valid->addValidator(new Zend_Validate_StringLength(3,100));
			if(!$valid->isValid($input))
			$this->_messages[$name] = '分类名称长度不得少于3字符大于100字符';
			return $input;
		}
		
		# 信息内容 -----------------------------------------------------
		function entityContent($input, $name='entity_content')
		{
			$input = trim($input);
			if(empty($input))
			$this->_messages[$name] = '信息内容不能为空';
			return $input;
		}
		
		# 信息标题 -----------------------------------------------------
		function entityTitle($input, $name='entity_title')
		{
			$input = trim(strip_tags($input));
			
			$valid = $this->_valid;
			$valid->addValidator(new Zend_Validate_StringLength(3,100));
			if(!$valid->isValid($input))
			$this->_messages[$name] = '信息标题不能为空且长度不得少于3字符大于100字符';
			return $input;
		}
		
		# 信息日期 -----------------------------------------------------
		function entityDate($input, $name='entity_date')
		{
			$input = trim($input);
			
			if(!strtotime($input))
			$this->_messages[$name] = '信息时间格式错误';
			return strtotime($input);
		}
		
		# 信息标签 -----------------------------------------------------
		function entityTag($input, $name='entity_tag')
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