<?php
define('ANNOTATE_PLUGIN_DIR',dirname(__FILE__));
define('ANNOTATE_HELPERS_DIR',ANNOTATE_PLUGIN_DIR.'/helpers');

require_once ANNOTATE_PLUGIN_DIR.'/AnnotatePlugin.php';
require_once ANNOTATE_HELPERS_DIR.'/helperFunctions.php';
 new AnnotatePlugin;

