<?php 

	class CacheModel
	{
		static function init($serialization = false, $time)
		{
			$frontendOptions = array(
   			'lifetime' => $time,                  // cache lifetime of half a minute
   			'automatic_serialization' => $serialization  // this is default anyway
			);

			$backendOptions = array('cache_dir' => '../../cache');
			return Zend_Cache::factory('Output', 'File', $frontendOptions, $backendOptions);
		}
	}