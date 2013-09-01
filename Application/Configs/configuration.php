<?php

return array
(
	// production, test, debug
	'environment'          => 'debug',
	'timezone'             => 'Asia/Novosibirsk',
	'locale'               => 'ru',
	'language'             => 'ru',
	'isset_languages'      => array
	(
		'ru' => array('ru', 'be', 'uk'),
		'en' => array('en')
	),
	'modules'              => array('Logger', 'Assets', 'Database', 'Validation', 'Auto', 'Mailer'),
	'templates_error_path' => APPLICATION_PATH . 'Templates' . DS,
);