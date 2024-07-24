<?php

class Upload
{//{{{
	
	static function page()
	{//{{{
		$php_ini_loaded_file = php_ini_loaded_file();

		$post_max_size = ini_get('post_max_size');
		$upload_max_filesize = ini_get('upload_max_filesize');

		$body = 
///////////////////////////////////////////////////////////////{{{//
<<<HEREDOC
<b>Upload files php parameters</b><br />

php_ini_loaded_file = {$php_ini_loaded_file}</br>
post_max_size = {$post_max_size}<br />
upload_max_filesize = {$upload_max_filesize}

HEREDOC;
///////////////////////////////////////////////////////////////}}}//
		
		$csrf_input = HTML::generate_csrf_input();
		$url_path = HTML::get_url_path();
		$body .= 
///////////////////////////////////////////////////////////////{{{//
<<<HEREDOC
<hr />
<form action="{$url_path}" method="post" enctype="multipart/form-data">
	{$csrf_input}
	
	<label>
		File for upload<br />
		<input name="file" type="file" />
	</label><br />
	
	<label>
		Path to upload<br />
		<input name="path" value="/tmp" size="48" />
	</label><br />
	
	<input value="Upload" type="submit" />
</form>

HEREDOC;
///////////////////////////////////////////////////////////////}}}//

		return($body);
	}//}}}
	
	static function action()
	{//{{{
		$file = array_get_array('file', $_FILES);
		if(!is_array($file)) {
			trigger_error("Incorrect array 'file' in '_FILES' array", E_USER_WARNING);
			return(false);
		}

		$error = array_get_int('error', $file);
		if(!is_int($error)) {
			trigger_error("Incorrect int 'error' in 'file' array", E_USER_WARNING);
			return(false);
		}
		if($error !== 0) {
			trigger_error("File uploading error", E_USER_WARNING);
			return(false);
		}

		$name = array_get_string('name', $file);
		if(!is_string($name)) {
			trigger_error("Incorrect string 'name' in 'file' array", E_USER_WARNING);
			return(false);
		}

		$tmp_name = array_get_string('tmp_name', $file);
		if(!is_string($tmp_name)) {
			trigger_error("Incorrect string 'tmp_name' in 'file' array", E_USER_WARNING);
			return(false);
		}

		$path = array_get_string('path', $_POST);
		if(!is_string($path)) {
			trigger_error("Incorrect string 'path' in '_POST' array", E_USER_WARNING);
			return(false);
		}
		$path = rtrim($path, '/');
		
		$return = is_uploaded_file($tmp_name);
		if(!$return) {
			trigger_error("tmp file is not uploaded", E_USER_WARNING);
			return(false);
		}
		
		$path = "{$path}/{$name}";
		$return = move_uploaded_file($tmp_name, $path);
		if(!$return) {
			trigger_error("Can't move upload file", E_USER_WARNING);
			return(false);
		}
		
		$url_path = HTML::get_url_path();
		$_ = ["path" => htmlentities($path)];
		$body = 
///////////////////////////////////////////////////////////////{{{//
<<<HEREDOC
<b>File uploaded to</b><br />
<input type="text" value="{$_["path"]}" size="48" /><br />
<a href="{$url_path}"><button>Back</button>

HEREDOC;
///////////////////////////////////////////////////////////////}}}//

		return($body);
	}//}}}
	
}//}}}

