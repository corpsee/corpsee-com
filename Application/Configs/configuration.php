<?php

/*return array
(
	'database_settings'   => 'sqlite:' . ROOT_PATH . 'Application' . DS . 'corpsee.sqlite',
	'log_path'            => ROOT_PATH . 'Logs' . DS,
	'cache_path'          => ROOT_PATH . 'Cache' . DS,
	'yuicompressor_path'  => ROOT_PATH . 'yuicompressor-2.4.7.jar',
	'java_path'           => 'C:\\Program files\\Java\\jre6\\bin\\java.exe',
);*/

return array
(
	// production, test, debug
	'environment' => 'debug',
	'timezone'    => 'Asia/Novosibirsk',
	'locale'      => 'ru',
	'language'    => 'ru',
	'modules'     => array
	(
		'Logger', 'Assets', 'Database', 'Validation', 'Auto', 'Mailer',
	),
);