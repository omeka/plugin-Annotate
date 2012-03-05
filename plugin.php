<?php
define('ANNOTATE_PLUGIN_DIR',dirname(__FILE__));
require_once ANNOTATE_PLUGIN_DIR.'/AnnotatePlugin.php';

$annotate =  new AnnotatePlugin;
$annotate->setUp();
