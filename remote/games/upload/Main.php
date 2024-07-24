<?php

class Main
{//{{{
	function __construct()
	{//{{{
		$request_method = array_get_string('REQUEST_METHOD', $_SERVER);
		if(!is_string($request_method)) {
			trigger_error("Can't get http request method", E_USER_WARNING);
			return(false);
		}
		
		switch($request_method) {
			case('GET'):
				$return = $this->handle_get_request();
				if($return !== true) {
					trigger_error("Handle get request failed", E_USER_ERROR);
					exit(255);
				}
				
				$HTML = new HTML;
				exit(0);
				
			case('POST'):
				$return = $this->handle_post_request();
				if($return !== true) {
					trigger_error("Handle post request failed", E_USER_ERROR);
					exit(255);
				}
				$HTML = new HTML;
				exit(0);
				
			default:
				if(defined('DEBUG') && DEBUG) var_dump(['$request_method' => $request_method]);
				trigger_error("Unsupported request method", E_USER_ERROR);
				exit(255);
		}
	}//}}}
	
	function handle_get_request()
	{//{{{
		require_once(__DIR__.'/Upload.php');
		HTML::$body .= Upload::page();
		return(true);
	}//}}}
	
	function handle_post_request()
	{//{{{
		require_once(__DIR__.'/Upload.php');
		$return = Upload::action();
		if(!is_string($return)) {
			trigger_error("File upload action failed", E_USER_WARNING);
			return(false);
		}
		HTML::$body .= $return;
		return(true);
	}//}}}
	
}//}}}

