<?php

	class Cmd
	{
		# 是否为班级成员
		static function isMember($class_id)
		{
			return Zend_Registry::get('sessClass')->data[$class_id] == null ? false : true;
		}
		
		# 是否为班级管理员
		static function isManager($class_id)
		{
			if(Zend_Registry::get('sessClass')->data[$class_id]['class_charge'] != Zend_Registry::get('sessCommon')->login['uid'] && Zend_Registry::get('sessClass')->data[$class_id]['class_member_charge'] != 1 ) 
			return false;
			else return true;
		}
		
		# 是否为建立者
		static function isCreater($class_id)
		{
			return Zend_Registry::get('sessClass')->data[$class_id]['class_charge'] == Zend_Registry::get('sessCommon')->login['uid'] ? true : false;
		}
	}