<?php

class Process
{
	
	static function ddgr(string $query)
	{//{{{//
		$return = Action::ddgr_exec($query);
		if(!is_string($return)) {//{{{//
			//if (defined('DEBUG') && DEBUG) var_dump(['' => ]);
			trigger_error("Can't exec 'ddgr'", E_USER_WARNING);
			return(false);
		}//}}}//
		$output = $return;
		
		$return = Check::ddgr_output($output);
		if(!is_array($return)) {//{{{//
			//if (defined('DEBUG') && DEBUG) var_dump(['' => ]);
			trigger_error("Check 'ddgr' output failed", E_USER_WARNING);
			return(false);
		}//}}}//
		$data = $return;
		
		$return = Data::ddgr_save($data);
		if(!$return) {//{{{//
			//if (defined('DEBUG') && DEBUG) var_dump(['' => ]);
			trigger_error("Can't save 'ddgr' data", E_USER_WARNING);
			return(false);
		}//}}}//
		
		foreach($data as $key => $item) {
			label_get_domain:
			$return = Data::domain_get($item["domain"]);
			if($return === NULL) {
				$return = Data::domain_new($item["domain"]);
				if(!is_int($return)) {//{{{//
					//if (defined('DEBUG') && DEBUG) var_dump(['' => ]);
					trigger_error("Can't add new 'domain' to database", E_USER_WARNING);
					return(false);
				}//}}}//
				goto label_get_domain;
			}
			if(!is_array($return)) {//{{{//
				//if (defined('DEBUG') && DEBUG) var_dump(['' => ]);
				trigger_error("Can't get 'domain' from database", E_USER_WARNING);
				return(false);
			}//}}}//
			$domain = $return;
			
			$data[$key]["id"] = $domain["id"];
			$data[$key]["domain"] = $domain["domain"];
		}
		
		return($data);
	}//}}}//

}

