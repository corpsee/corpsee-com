<?php

return array
(
	'environment'         => 'production', // production,test,debug
	'minify_assets'       => FALSE,
	'timezone'            => 'Asia/Novosibirsk',
	'charset'             => 'UTF-8',
	'locale'              => 'ru',
	'http_port'           => 80,
	'https_port'          => 443,
	'templates_path'      => TEMPLATE_PATH,
	'templates_extension' => '.tpl',
	'database_settings'   => 'sqlite:' . ROOT_PATH . 'Application' . DS . 'corpsee.sqlite',
	'services'            => array
	(
		'database'   => 'Framework\\ServiceProvider\\DatabaseProvider',
		'validation' => 'Framework\\ServiceProvider\\ValidationProvider',
		'auto'       => 'Framework\\ServiceProvider\\AutoProvider',
		'mailer'     => 'Framework\\ServiceProvider\\SwiftmailerProvider',
		'logger'     => 'Framework\\ServiceProvider\\MonologProvider',
	),
	//TODO: вынести в отдельные конфиги модулей
	'log_path'            => ROOT_PATH . 'Logs' . DS,
	'cache_path'          => ROOT_PATH . 'Cache' . DS,
	'yuicompressor_path'  => ROOT_PATH . 'yuicompressor-2.4.7.jar',
	'java_path'           => 'C:\\Program files\\Java\\jre6\\bin\\java.exe',
);