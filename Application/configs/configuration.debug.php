<?php

return array
(
	// production, test, debug
	'environment'          => 'debug',
	'timezone'             => 'Asia/Novosibirsk',
	'locale'               => 'ru',
	'language'             => 'ru',
	'error_controller'     => 'Application\\Controller\\ErrorController::error',

	'modules'              => array('Logger', 'Assets', 'Database', 'Validation', 'Auto', 'Mailer'),

	'assets'               => array
	(
		'java_path'  => 'java',
		'lessjs_url' => FILE_PATH_URL . 'lib/less/1.5.1/less.min.js',
	),

	'assets.packages' => array
	(
		'jquery' => array
		(
			'js'  => FILE_PATH_URL . 'lib/jquery/1.10.2/jquery.js',
		),
		'jquery-ui' => array
		(
			'css' => FILE_PATH_URL . 'lib/jquery-ui/1.10.3/themes/base/jquery-ui.css',
			'js'  => FILE_PATH_URL . 'lib/jquery-ui/1.10.3/ui/jquery-ui.js',
		),
		'jcrop' => array
		(
			'css' => FILE_PATH_URL . 'lib/jcrop/0.9.12/css/jcrop.css',
			'js'  => FILE_PATH_URL . 'lib/jcrop/0.9.12/js/jcrop.js',
		),
		'chosen' => array
		(
			'css' => FILE_PATH_URL . 'lib/chosen/1.0.0/chosen.css',
			'js'  => FILE_PATH_URL . 'lib/chosen/1.0.0/chosen.js',
		),
		'bootstrap' => array
		(
			'css' => FILE_PATH_URL . 'lib/bootstrap/2.3.2/css/bootstrap.css',
			'js'  => FILE_PATH_URL . 'lib/bootstrap/2.3.2/js/bootstrap.js',
		),
		'less' => array
		(
			'js'  => FILE_PATH_URL . 'lib/less/1.5.1/less.js',
		),
		'lightbox' => array
		(
			'css' => FILE_PATH_URL . 'lib/lightbox/2.6-custom/css/lightbox.css',
			'js'  => FILE_PATH_URL . 'lib/lightbox/2.6-custom/js/lightbox.js',
		),
		'normalize' => array
		(
			'css' => FILE_PATH_URL . 'lib/normalize/1.1.3/normalize.css',
		),
	),

	'database' => array
	(
		'type' => 'sqlite',
		'dns'  => 'sqlite:' . APPLICATION_PATH . 'corpsee.sqlite',
	),

	'logger' => array
	(
		'name' => 'corpsee.local',
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
			'corpsee' => array
			(
				'password' => '$2a$08$THJnQkdscWJoTTRLM1dhROYLSMHrCV4JzWQksIujQjCT1cq25OxxG',
				'groups'   => array
				(
					'ROLE_ADMIN',
					'ROLE_REGISTERED',
				),
			),
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

	'isset_languages' => array
	(
		'ru' => array('ru', 'be', 'uk'),
		'en' => array('en'),
	),
);