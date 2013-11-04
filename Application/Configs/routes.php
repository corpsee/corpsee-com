<?php

return array
(
	// ErrorController
	'error' => array
	(
		'pattern'      => '/error/{code}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\ErrorController::errorServer',
			'code'  => 500,
		),
		'requirements' => array
		(
			'code' => '\d{3}',
		),
	),
	'admin_error' => array
	(
		'pattern'      => '/admin/error/{code}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\ErrorController::errorAdmin',
			'code'  => 0,
		),
		'requirements' => array
		(
			'code' => '\d{1,2}',
		),
	),

	// BioController
	'bio_index' => array
	(
		'pattern'      => '/{language_prefix}/{bio_index}',
		'defaults'     => array
		(
			'_controller'     => 'Application\\Controller\\BioController::index',
			'bio_index'       => '',
			'language_prefix' => '',
		),
		'requirements' => array
		(
			'bio_index'       => '(bio|bio/index)?',
			'language_prefix' => '\w{2}?',
		),
	),

	// IndexController
	'gallery_list' => array
	(
		'pattern'      => '/{language_prefix}/gallery{index_gallery}',
		'defaults'     => array
		(
			'_controller'     => 'Application\\Controller\\GalleryController::listItems',
			'index_gallery'   => '',
		),
		'requirements' => array
		(
			'index_gallery'   => '(/index|/list)?',
			'language_prefix' => '\w{2}',
		),
	),
	'gallery_bytag' => array
	(
		'pattern'      => '/{language_prefix}/gallery/bytag',
		'defaults'     => array
		(
			'_controller'     => 'Application\\Controller\\GalleryController::bytag',
		),
		'requirements' => array
		(
			'language_prefix' => '\w{2}',
		),
	),
	'gallery_one_tag' => array
	(
		'pattern'      => '/{language_prefix}/gallery/onetag/{tag}',
		'defaults'     => array
		(
			'_controller'     => 'Application\\Controller\\GalleryController::onetag',
			'tag'             => '',
		),
		'requirements' => array
		(
			'language_prefix' => '\w{2}',
		),
	),

	// AdminController
	'admin_login' => array
	(
		'pattern'      => '/admin{index_login}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\AdminController::login',
			'index_login' => '',
		),
		'requirements' => array
		(
			'index_login' => '(/index|/login)?'
		),
	),
	'admin_logout' => array
	(
		'pattern'      => '/admin/logout',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\AdminController::logout',
		),
	),

	// GalleryController
	'admin_gallery_list' => array
	(
		'pattern'      => '/admin/gallery{index_list}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\AdminGalleryController::listItems',
			'index_list'  => '',
		),
		'requirements' => array
		(
			'index_list' => '(/index|/list)?'
		),
	),
	'admin_gallery_add' => array
	(
		'pattern'      => '/admin/gallery/add',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\AdminGalleryController::add',
		),
	),
	'admin_gallery_crop' => array
	(
		'pattern'      => '/admin/gallery/crop/{image}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\AdminGalleryController::crop',
			'image'       => NULL,
		),
	),
	'admin_gallery_result' => array
	(
		'pattern'      => '/admin/gallery/result/{image}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\AdminGalleryController::result',
			'image'       => NULL,
		),
	),
	'admin_gallery_edit' => array
	(
		'pattern'      => '/admin/gallery/edit/{id}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\AdminGalleryController::edit',
			'id'       => NULL,
		),
	),
	'admin_gallery_editimage' => array
	(
		'pattern'      => '/admin/gallery/editimage/{id}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\AdminGalleryController::editimage',
			'id'       => NULL,
		),
	),
	'admin_gallery_delete' => array
	(
		'pattern'      => '/admin/gallery/delete/{id}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\AdminGalleryController::delete',
			'id'       => NULL,
		),
	),

	// TagController
	'admin_tag_list' => array
	(
		'pattern'      => '/admin/tag{index_list}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\AdminTagController::listItems',
			'index_list'  => '',
		),
		'requirements' => array
		(
			'index_list' => '(/index|/list)?'
		),
	),
	'admin_tag_add' => array
	(
		'pattern'      => '/admin/tag/add',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\AdminTagController::add',
		),
	),
	'admin_tag_edit' => array
	(
		'pattern'      => '/admin/tag/edit/{id}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\AdminTagController::edit',
			'id'         => NULL,
		),
	),
	'admin_tag_delete' => array
	(
		'pattern'      => '/admin/tag/delete/{id}',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\AdminTagController::delete',
			'id'         => NULL,
		),
	),

	// TypographyController
	'typography' => array
	(
		'pattern'      => '/typography',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\TypographyController::index',
		),
	),

	// ToolController
	'tool_password' => array
	(
		'pattern'      => '/tool/password',
		'defaults'     => array
		(
			'_controller' => 'Application\\Controller\\ToolController::password',
		),
	),
);