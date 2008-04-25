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
		
		# 班级话题标签
		function topicTag($input, $name='entity_tag')
		{
			if(null != $input && is_array($input))
			{
				$input = implode(',',$input);
				$input = trim(strip_tags($input));
			}
			return $input;
		}
		
		# 班级话题标题
		function topicTitle($input, $name = 'topic_title')
		{
			$input = strip_tags(trim($input));
			$valid = $this->_valid;
			$valid->addValidator(new Zend_Validate_StringLength(4, 100));
			if(!$valid->isValid($input))
			{
				$this->_messages[$name] = '标题长度范围在4到100之间(中文2到50之间)';
			}
			return $input;
		}
		
		# 不能为空
		function noEmpty($input, $name)
		{
			$input = trim($input);
			if(empty($input))
			{
				$this->_messages[$name] = $name.'不能为空';
			}
			return $input;
		}
		
		# 班级名称 
		function className($input, $name = 'class_name')
		{
			$input = strip_tags(trim($input));
			$valid = $this->_valid;
			$valid->addValidator(new Zend_Validate_StringLength(4, 50));
			if(!$valid->isValid($input))
			{
				$this->_messages[$name] = '班级名称长度范围在4到50之间(中文2到25之间)';
			}
			elseif (false != DbModel::isClassExist($input))
			{
				$this->_messages[$name] = $input.' 该名称的班级已经存在';
			}
			return $input;
		}
		
		
		# 获取校验信息
		function getMessages()
		{
			return $this->_messages;
		}
	}