<?php if ( ! defined('DIR_CONFIG')) exit('No direct script access allowed');

return array(

'uri' => array(
	'class' => 'uri',
	'path' => 'core'
),

'config' => array(
	'class' => 'Config',
	'path' => 'core'
),

'input' => array(
	'class' => 'Request',
	'path' => 'core'
),

'force' => array(
	'class' => 'force',
	'path' => 'core'
),

'mobile' => array(
	'class' => 'isMobile',
	'path' => 'core'
),

'postValidation' => array(
	'class' => 'postValidation',
	'path' => 'core'
),

'getValidation' => array(
	'class' => 'getValidation',
	'path' => 'core'
)

);