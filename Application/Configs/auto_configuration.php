<?php

return array
(
	'auto.access' => array
	(
		'Application\\Controller\\GalleryController' => array
		(
			'index'     => array('ROLE_REGISTERED'),
			'add'       => array('ROLE_ADMIN'),
			'crop'      => array('ROLE_ADMIN'),
			'result'    => array('ROLE_ADMIN'),
			'edit'      => array('ROLE_ADMIN'),
			'editimage' => array('ROLE_ADMIN'),
			'delete'    => array('ROLE_ADMIN'),
		),
		'Application\\Controller\\TagController' => array
		(
			'index'  => array('ROLE_REGISTERED'),
			'add'    => array('ROLE_ADMIN'),
			'edit'   => array('ROLE_ADMIN'),
			'delete' => array('ROLE_ADMIN'),
		),
		'Application\\Controller\\ErrorController' => array
		(
			'errorAdmin'  => array('ROLE_REGISTERED'),
		),
	),
	'auto.users' => array
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
);