<?php if ( ! defined('DIR_CONFIG')) exit('No direct script access allowed');

return array(

'index' => 'main:index',

'data/config' => 'configs:index',

'data/config/(.*)' => 'configs:$1',

'app.js' => 'app:index',

'logs/download/(:num)' => 'logs:download:$1',

'logs/ajax/(information|delete|deleteAll|deleteLog|transfer|addTransfer|deleteTransfer|change|selectUser|sulp|AllTransfer|selectUserAll)' => 'logs:ajax:$1',

'logs/(:num)' => 'logs:index:$1',

'api/(.*).(get|post)' => 'api:$1:$2',

'editor/(:num)' => 'editor:index:$1',

'editor/system/(:num)' => 'editor:get_files:$1',

'editor/text/(:num)' => 'editor:get_file:$1',

'editor/search/(:num)' => 'editor:get_search:$1',

'editor/text/(home)/(:num)' => 'editor:get_file:$2:$1'

);