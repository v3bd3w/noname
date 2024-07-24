<?php
define('DEBUG', true);
require_once('/usr/games/config.php');

set_include_path("{$_SERVER['DOCUMENT_ROOT']}/include");
require_once('block/http_init.php');
require_once('class/HTML.php');
require_once('class/DB.php');
require_once('block/db_init.php');

require_once('function/array_get_type.php');
require_once('function/import_export.php');

require_once(__DIR__.'/Main.php');
require_once(__DIR__.'/Explorer.php');
require_once(__DIR__.'/Data.php');
require_once(__DIR__.'/Page.php');
require_once(__DIR__.'/Action.php');
require_once(__DIR__.'/Check.php');
require_once(__DIR__.'/Process.php');

$url_path = HTML::get_url_path();
HTML::$body .= 
///////////////////////////////////////////////////////////////{{{//
<<<HEREDOC
<a href="{$url_path}"><button>Index</button></a>
<a href="{$url_path}?page=current"><button>Current</button></a>
<a href="{$url_path}?page=dir"><button>Dir</button></a>
<a href="{$url_path}?page=install"><button>Install</button></a>
<hr />
HEREDOC;
///////////////////////////////////////////////////////////////}}}//

Explorer::$config = CONFIG["explorer"];
$Main = new Main();

