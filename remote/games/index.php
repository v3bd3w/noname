<?php
define('DEBUG', true);

set_include_path("{$_SERVER['DOCUMENT_ROOT']}/include");
require_once('block/http_init.php');
require_once('class/HTML.php');

HTML::$body = 
///////////////////////////////////////////////////////////////{{{//
<<<HEREDOC
<a href="/upload/"><button>Upload</button></a>
<a href="/explorer/"><button>Explorer</button></a>
HEREDOC;
///////////////////////////////////////////////////////////////}}}//

$HTML = new HTML();

