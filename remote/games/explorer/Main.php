<?php

class Main
{

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
		$page = @array_get_string('page', $_GET);
		if(!is_string($page)){
			$page = 'index';
		}
		switch($page) {
			case('install'):
				return($this->install());
			case("ddgr"):
				$return = $this->ddgr();
				if(!$return) {//{{{//
					//if (defined('DEBUG') && DEBUG) var_dump(['' => ]);
					trigger_error("Main 'ddgr' failed", E_USER_WARNING);
					return(false);
				}//}}}//
				break;
			case('current'):
				return($this->crnt());
			case("dir"):
				//{{{
				$dir = @array_get_string('dir', $_GET);
				if(!is_string($dir)) {
					$dir = array_get_string('data_dir', Explorer::$config);
					if(!is_string($dir)) {
						if (defined('DEBUG') && DEBUG) var_dump(['Explorer::$config' => Explorer::$config]);
						trigger_error("Can't get string 'data_dir' from 'Explorer::config' array", E_USER_WARNING);
						return(false);
					}
				}
				
				$return = Explorer::dir($dir);
				if(!$return) {
					trigger_error("Can't print directory content", E_USER_WARNING);
					return(false);
				}
				break;
				//}}}
			case('view'):
				//{{{
				$file = array_get_string('file', $_GET);
				if(!is_string($file)) {//{{{
					trigger_error("Can't get string 'file' from '_GET' array", E_USER_WARNING);
					return(false);
				}//}}}
				
				$return = Explorer::view($file);
				if(!$return) {//{{{
					trigger_error("View file in explorer failed", E_USER_WARNING);
					return(false);
				}//}}}
				break;
				//}}}
		}
		return(true);
	}//}}}
	
	function handle_post_request()
	{//{{{
		return(true);
	}//}}}
	
	function crnt()
	{//{{{//
		$return = import(Explorer::$config["data_dir"].'/input.json');
		if(!is_array($return)) {//{{{//
			//if (defined('DEBUG') && DEBUG) var_dump(['' => ]);
			trigger_error("Can't import data", E_USER_WARNING);
			return(false);
		}//}}}//
		$name_id = $return["name_id"];
		$ddgr_result = $return["ddgr_result"];
		
		$return = Explorer::process_ddgr_result($name_id, $ddgr_result);
		if(!is_array($return)) {//{{{//
			//if (defined('DEBUG') && DEBUG) var_dump(['' => ]);
			trigger_error("Can't process 'ddgr' result", E_USER_WARNING);/*{{{*//*}}}*/
			return(false);
		}//}}}//
		
		return(true);
	}//}}}//
	
	function ddgr()
	{//{{{//
		$return = Data::get_next_name();
		if(!is_array($return)) {//{{{//
			//if (defined('DEBUG') && DEBUG) var_dump(['' => ]);
			trigger_error("Can't get next name", E_USER_WARNING);
			return(false);
		}//}}}//
		$name_id = $return["id"];
		$query = $return["name"];
		
		$return = Explorer::ddgr($query);
		if(!is_string($return))	{//{{{//
			//if (defined('DEBUG') && DEBUG) var_dump(['' => ]);
			trigger_error("Explorer::ddgr failed", E_USER_WARNING);
			return(false);
		}//}}}//
		$ddgr_result = $return;
/*		
		$return = Action::process_ddgr_result($name_id, $ddgr_result);
		if(!is_array($return)) {//{{{//
			//if (defined('DEBUG') && DEBUG) var_dump(['' => ]);
			trigger_error("Can't process ddgr result", E_USER_WARNING);
			return(false);
		}//}}}//

		$input = [
			"name_id" => $name_id,
			"ddgr_result" => $ddgr_result,
		];
		$return = export(Explorer::$config["data_dir"].'/input.json', $input);
		if(!$return) {//{{{//
			//if (defined('DEBUG') && DEBUG) var_dump(['' => ]);
			trigger_error("Can't exoprt input data", E_USER_WARNING);
			return(false);
		}//}}}//
*/		
		return(true);
	}//}}}//

	function install()
	{//{{{//
		$return = true; //Data::create_domain_table();
		if(!$return) {//{{{//
			//if (defined('DEBUG') && DEBUG) var_dump(['' => ]);
			trigger_error("Can't create 'domains' table", E_USER_WARNING);
			return(false);
		}//}}}//
		return(true);
	}//}}}//

}

