<?php  

define('base_dir', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('assets', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'assets/');
define('models', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'models/');
define('views', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views/');
define('sessions', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'sessions/');

$ds_explode = array_filter(explode(DIRECTORY_SEPARATOR, base_dir));
$project_name = '/' . end($ds_explode) . '/';
define('project_name', $project_name);

?>