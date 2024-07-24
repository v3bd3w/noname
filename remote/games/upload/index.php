<?php
define('DEBUG', true);

set_include_path("{$_SERVER['DOCUMENT_ROOT']}/include");
require_once('block/http_init.php');
require_once('class/HTML.php');

require_once('function/array_get_type.php');

HTML::$title = "Upload";

require_once(__DIR__.'/Main.php');
$Main = new Main();

