<?php

return array
(
	// production, test, debug
	'environment'          => 'test',
	'timezone'             => 'Asia/Novosibirsk',
	'locale'               => 'ru',
	'language'             => 'ru',
	'error_controller'     => 'Application\\Controller\\ErrorController::error',

	'modules'              => array('Logger', 'Assets', 'Database', 'Validation', 'Auto', 'Mailer'),

	'assets'               => array
	(
		'java_path'  => 'C:\\Program files\\Java\\jre6\\bin\\java.exe',
		'lessjs_url' => FILE_PATH_URL . 'lib/less/1.4.1/less.min.js',
	),

	'database' => array
	(
		'type' => 'sqlite',
		'dns'  => 'sqlite:' . ROOT_PATH . 'Application' . DS . 'corpsee.sqlite',
	),

	'logger' => array
	(
		'name' => 'corpsee.com',
	),

	'validation' => array
	(
		'rules' => array
		(
			'GalleryForm' => array
			(
				'title'       => array('noempty'),
				'description' => array('noempty'),
				'tags'        => array('noempty'),
				'create_date' => array('noempty'),
			),
			'TagForm' => array
			(
				'tag' => array('noempty'),
			),
			'UserForm' => array
			(
				'login'    => array('noempty'),
				'email'    => array('noempty', 'email'),
				'password' => array('noempty', array('min_length', 6)),
			),
		),
	),

	'auto' => array
	(
		'access' => array
		(
			'Application\\Controller\\AdminGalleryController' => array
			(
				'listItems' => array('ROLE_REGISTERED'),
				'add'       => array('ROLE_ADMIN'),
				'crop'      => array('ROLE_ADMIN'),
				'result'    => array('ROLE_ADMIN'),
				'edit'      => array('ROLE_ADMIN'),
				'editimage' => array('ROLE_ADMIN'),
				'delete'    => array('ROLE_ADMIN'),
			),
			'Application\\Controller\\AdminTagController' => array
			(
				'listItems' => array('ROLE_REGISTERED'),
				'add'       => array('ROLE_ADMIN'),
				'edit'      => array('ROLE_ADMIN'),
				'delete'    => array('ROLE_ADMIN'),
			),
		),
		'users' => array
		(
			// 201986
			'corpsee' => array
			(
				'password' => '$2a$08$THJnQkdscWJoTTRLM1dhROYLSMHrCV4JzWQksIujQjCT1cq25OxxG',
				'groups'   => array
				(
					'ROLE_ADMIN',
					'ROLE_REGISTERED',
				),
			),
			// registered
			'registered' => array
			(
				'password' => '$2a$08$bEJzRGI4TDFmNzN4SjdoUObn292Fg8/yEohQCWDTLsWP1Qkj0OmeG',
				'groups'   => array
				(
					'ROLE_REGISTERED',
				),
			),
		),
	),

	'routes' => include_once 'routes.php',

	'isset_languages'      => array
	(
		'ru' => array('ru', 'be', 'uk'),
		'en' => array('en'),
	),
);