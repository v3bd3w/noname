<?php

class Explorer
{

	static $config = [];
	
	static function dir(string $dir)
	{//{{{
		$_ = [];
		$body = "";
		
		$cwd = realpath($dir);
		if(!is_string($cwd)) {
			if (defined('DEBUG') && DEBUG) var_dump(['$dir' => $dir]);
			trigger_error("Can't get real path from passed dir", E_USER_WARNING);
			return(false);
		}
		
		$return = chdir($cwd);
		if(!$return) {
			if (defined('DEBUG') && DEBUG) var_dump(['$cwd' => $cwd]);
			trigger_error("Can't change to real path of passed dir", E_USER_WARNING);
			return(false);
		}
		
		$_["cwd"] = htmlentities($cwd);
		$url_path = HTML::get_url_path();
		$body .= 
///////////////////////////////////////////////////////////////{{{//
<<<HEREDOC
<form action="{$url_path}" method="get">
	<input name="page" value="dir" type="hidden" />
	<label>
		CWD
		<input name="dir" value="{$_["cwd"]}" size=48 />
	</label>
	<input value="Go" type="submit" />
</form>

HEREDOC;
///////////////////////////////////////////////////////////////}}}//
		
		$NAME = scandir($cwd);
		if(!is_array($NAME)) {
			if (defined('DEBUG') && DEBUG) var_dump(['$cwd' => $cwd]);
			trigger_error("Can't scan CWD", E_USER_WARNING);
			return(false);
		}
		
		foreach($NAME as $name) 
		{
			$path = "{$cwd}/{$name}";
			$_["path"] = urlencode($path);
			$_["name"] = htmlentities($name);
			
			if(is_link($path)) {
				$body .= 
///////////////////////////////////////////////////////////////{{{//
<<<HEREDOC
<br />
&amp;<a
 href="{$url_path}?page=link&file={$_["path"]}"
 class="name"
>{$_["name"]}</a>

HEREDOC;
///////////////////////////////////////////////////////////////}}}//
			}
			elseif(is_dir($path)) {
				$body .= 
///////////////////////////////////////////////////////////////{{{//
<<<HEREDOC
<br />
<a 
 href="{$url_path}?page=dir&dir={$_["path"]}"
 class="name"
>{$_["name"]}</a>/

HEREDOC;
///////////////////////////////////////////////////////////////}}}//
			}
			elseif(is_file($path)) {
				$body .= 
///////////////////////////////////////////////////////////////{{{//
<<<HEREDOC
<br />
<a
 href="{$url_path}?page=view&file={$_["path"]}"
 class="name"
>{$_["name"]}</a>

HEREDOC;
///////////////////////////////////////////////////////////////}}}//
			}
			else {
				$body .= 
///////////////////////////////////////////////////////////////{{{//
<<<HEREDOC
<br />
?<a
 class="name"
>{$_["name"]}</a>

HEREDOC;
///////////////////////////////////////////////////////////////}}}//
			}
		}
		
		HTML::$style .= 
///////////////////////////////////////////////////////////////{{{//
<<<HEREDOC
a[class='name'] {
	line-height: 24px;
	font-size: 18px;
	text-decoration: none;
	padding: 2px;
}

HEREDOC;
///////////////////////////////////////////////////////////////}}}//
		
		HTML::$body .= $body.'<hr />';
		return(true);
	}//}}}

	static function view(string $file)
	{//{{{
		$contents = file_get_contents($file);
		if(!is_string($contents)) {//{{{
			if (defined('DEBUG') && DEBUG) var_dump(['$file' => $file]);
			trigger_error("Can't get contents from for view", E_USER_WARNING);
			return(false);
		}//}}}
		
		$_ = [
			'contents' => htmlentities($contents),
		];
		$_["contents"] = preg_replace(
			"/\t/", 
			'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 
			$_["contents"]
		);
		$body = 
///////////////////////////////////////////////////////////////{{{//
<<<HEREDOC
<pre>{$_["contents"]}</pre>

HEREDOC;
///////////////////////////////////////////////////////////////}}}//
		
		HTML::$style .= 
///////////////////////////////////////////////////////////////{{{//
<<<HEREDOC
pre {
	font-family: Monospace;
}

HEREDOC;
///////////////////////////////////////////////////////////////}}}//
		HTML::$body .= $body;
		return(true);
	}//}}}

	static function ddgr(string $query)
	{//{{{//
		$_ = ["query" => escapeshellarg($query)];
		$command = 
			'ddgr'
			.' --noua'
			.' --np'
			.' --json'
			.' --unsafe'
			.' --reg=us-en'
			." {$_["query"]}"
		;
		$output = [];
		$status = 0;
		$return = exec($command, $output, $status);
		if($return === false || $status !== 0) {
			if (defined('DEBUG') && DEBUG) var_dump([$return, $command, $status]);
			trigger_error("Can't exec ddgr", E_USER_WARNING);
			return(false);
		}
		$output = implode("\n", $output);
		return($output);
	}//}}}//

	static function process_ddgr_result(int $name_id, string $ddgr_result)
	{//{{{//
		$return = json_decode($ddgr_result, true);
		if(!is_array($return)) {//{{{//
			//if (defined('DEBUG') && DEBUG) var_dump(['' => ]);
			trigger_error("Can't decode json 'ddgr_result'", E_USER_WARNING);
			return(false);
		}//}}}//
		$ddgr_result = $return;
		
		foreach($ddgr_result as $array) {
			if(!@is_string($array["url"])) {//{{{//
				//if (defined('DEBUG') && DEBUG) var_dump(['' => ]);
				trigger_error("Incorrect string 'url' in array item of 'ddgr_result'", E_USER_WARNING);
				continue;
			}//}}}//
			$url = $array["url"];
			$domain = parse_url($url, PHP_URL_HOST);
			$return = Data::get_domain_status($domain);
			if(!is_array($return)) {//{{{//
				//if (defined('DEBUG') && DEBUG) var_dump(['' => ]);
				trigger_error("Can't get domain status", E_USER_WARNING);
				continue;
			}//}}}//
			var_dump($return);
			
		}
		return([]);
	}//}}}//

}

