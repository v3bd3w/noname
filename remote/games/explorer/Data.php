<?php

class Data
{

	static $prefix = 'explorer';
	
	static function installation(int $stage)
	{//{{{//
		$DB = new DB();
		$_ = [];
		
		if($stage == 0) 
		{//{{{//
		
			$dir = array_get_string('data_dir', Explorer::$config);
			if(!is_string($dir)) {//{{{//
				if (defined('DEBUG') && DEBUG) var_dump(['Explorer::$config' => Explorer::$config]);
				trigger_error("Can't get string 'data_dir' from 'Explorer::config' array", E_USER_WARNING);
				return(false);
			}//}}}//
			
			$file = "{$dir}/input.json";
			$names = import($file);
			if(!is_array($names)) {//{{{//
				trigger_error("Can't import 'names' array", E_USER_WARNING);
				return(false);
			}//}}}//
			
			$sql = 
///////////////////////////////////////////////////////////////{{{//
<<<HEREDOC
DROP TABLE IF EXISTS `names`;
CREATE TABLE `names` (
	`id` INT AUTO_INCREMENT KEY,
	`name` TEXT,
	`status` INT
);
HEREDOC;
///////////////////////////////////////////////////////////////}}}//
			$return = $DB->queries($sql);
			if(!$return) {//{{{//
				//if (defined('DEBUG') && DEBUG) var_dump(['' => ]);
				trigger_error("Cna't perform database query", E_USER_WARNING);
				return(false);
			}//}}}//
			
			foreach($names as $name)
			{//{{{//
				$_["name"] = $DB->escape($name);
				$sql = 
///////////////////////////////////////////////////////////////{{{//
<<<HEREDOC
INSERT INTO `names` (
	`name`, 
	`status`
) VALUES (
	'{$_["name"]}',
	0
);
HEREDOC;
///////////////////////////////////////////////////////////////}}}//
				$return = $DB->query($sql);
				if(!$return) {//{{{//
					//if (defined('DEBUG') && DEBUG) var_dump(['' => ]);
					trigger_error("Can't perform database query", E_USER_WARNING);
					return(false);
				}//}}}//
			}//}}}//
			
			return(true);
		}//}}}//
		
		switch($stage) {
			case(1):
				return(self::create_domains_table());
		}
		return(false);
	}//}}}//

	static function get_next_name()
	{//{{{//
		$DB = new DB();
		$sql = 
///////////////////////////////////////////////////////////////{{{//
<<<HEREDOC
SELECT `id`,`name` 
 FROM `names`
 WHERE `status`=0 
 ORDER BY RAND() 
 LIMIT 1;
HEREDOC;
///////////////////////////////////////////////////////////////}}}//
		$array = $DB->query($sql);
		if(!is_array($array)) {//{{{//
			//if (defined('DEBUG') && DEBUG) var_dump(['' => ]);
			trigger_error("Can't perform database query", E_USER_WARNING);
			return(false);
		}//}}}//
		if(empty($array)) {//{{{//
			//if (defined('DEBUG') && DEBUG) var_dump(['' => ]);
			trigger_error("Database request given empty result", E_USER_WARNING);
			return(false);
		}//}}}//
		return($array[0]);
	}//}}}//

	static function create_domains_table()
	{//{{{//
		$DB = new DB();
		$sql = 
///////////////////////////////////////////////////////////////{{{//
<<<HEREDOC
DROP TABLE IF EXISTS `domains`;
CREATE TABLE `domains` (
	`id` INT AUTO_INCREMENT KEY,
	`domain` TEXT,
	`status` INT
);
HEREDOC;
///////////////////////////////////////////////////////////////}}}//
		$return = $DB->queries($sql);
		if(!$return) {//{{{//
			//if (defined('DEBUG') && DEBUG) var_dump(['' => ]);
			trigger_error("Can't perform database query", E_USER_WARNING);
			return(false);
		}//}}}//
		user_error("'domains' table created");
		return(true);
	}//}}}//

	static function get_domain_status(string $domain)
	{//{{{//
		$DB = new DB();
		$_ = [];
		
		$_["domain"] = $DB->escape($domain);
		
		label_select:
		$sql = 
///////////////////////////////////////////////////////////////{{{//
<<<HEREDOC
SELECT `id`, `domain`, `status`
 FROM `domains`
 WHERE `domain`='{$_["domain"]}'
 LIMIT 1;
HEREDOC;
///////////////////////////////////////////////////////////////}}}//
		$return = $DB->query($sql);
		if(!is_array($return)) {//{{{//
			//if (defined('DEBUG') && DEBUG) var_dump(['' => ]);
			trigger_error("Can't perform database query", E_USER_WARNING);
			return(false);
		}//}}}//
		
		if(@is_array($return[0])) {
			return($return[0]);
		}
		
		$sql = 
///////////////////////////////////////////////////////////////{{{//
<<<HEREDOC
INSERT INTO `domains`
	(`domain`, `status`)
 VALUES
	('{$_["domain"]}', 2);
HEREDOC;
///////////////////////////////////////////////////////////////}}}//
		$return = $DB->query($sql);
		if(!$return) {//{{{//
			//if (defined('DEBUG') && DEBUG) var_dump(['' => ]);
			trigger_error("Can't perform database query", E_USER_WARNING);
			return(false);
		}//}}}//
		
		goto label_select;
	}//}}}//

	static function create_ddgr_table()
	{//{{{//
		$DB = new DB();
		$_ = [];
		
		$_["table"] = self::$prefix.'.ddgr';
		$sql = 
///////////////////////////////////////////////////////////////{{{//
<<<HEREDOC
DROP TABLE IF EXISTS `{$_["table"]}`;
CREATE TABLE `{$_["table"]}` (
	`id` INT AUTO_INCREMENT KEY,
	`query` TEXT,
	`domain` TEXT,
	`url` TEXT,
	`title` TEXT,
	`abstract` MEDIUMTEXT
);
HEREDOC;
///////////////////////////////////////////////////////////////}}}//
		$return = $DB->queries($sql);
		if(!$return) {//{{{//
			//if (defined('DEBUG') && DEBUG) var_dump(['' => ]);
			trigger_error("Can't perform database query", E_USER_WARNING);
			return(false);
		}//}}}//
		user_error("'{$_["table"]}' table created");
		return(true);
	}//}}}//

	static function create_domain_table()
	{//{{{//
		$DB = new DB();
		$_ = [];
		
		$_["table"] = self::$prefix.'.domain';
		$sql = 
///////////////////////////////////////////////////////////////{{{//
<<<HEREDOC
DROP TABLE IF EXISTS `{$_["table"]}`;
CREATE TABLE `{$_["table"]}` (
	`id` INT AUTO_INCREMENT KEY,
	`domain` TEXT,
	`status` INT
);
HEREDOC;
///////////////////////////////////////////////////////////////}}}//
		$return = $DB->queries($sql);
		if(!$return) {//{{{//
			//if (defined('DEBUG') && DEBUG) var_dump(['' => ]);
			trigger_error("Can't perform database query", E_USER_WARNING);
			return(false);
		}//}}}//
		user_error("'{$_["table"]}' table created");
		return(true);
	}//}}}//

}

