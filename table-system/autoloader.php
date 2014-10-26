<?php
	
	$TABLE_SYSTEM_OPTIONS = array();
	
	spl_autoload_register(function ($class) {
		
		$exploded = explode('\\', $class);

		//we need at least a single form
		if(count($exploded) <= 1){
			return false;
		}

		//searching for a specific form using a search engine
		if($exploded[0] == 'tm'){
			$name = __DIR__.'/assets/'.$exploded[1].'.class.php';
			if(is_file($name)){
				require_once($name);
				return true;
			}
		}
	},false);
	
	//include the config file if it is available
	if(is_file(__DIR__.'/config.php')){
		require_once(__DIR__.'/config.php');
	}
?>