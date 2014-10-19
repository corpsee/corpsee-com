<?php

return [
    // ErrorController
    'error' => [
        'pattern'  => '/error/{code}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\ErrorController::errorServer',
            'code'        => 500,
        ],
        'requirements' => [
            'code' => '\d{3}',
        ],
    ],
    'admin_error' => [
        'pattern'  => '/admin/error/{code}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\ErrorController::errorAdmin',
            'code'        => 0,
        ],
        'requirements' => [
            'code' => '\d{1,2}',
        ],
    ],
    // BioController
    'bio_index' => [
        'pattern'  => '/{language_prefix}/{bio_index}',
        'defaults' => [
            '_controller'     => 'Application\\Controller\\BioController::index',
            'bio_index'       => '',
            'language_prefix' => '',
        ],
        'requirements' => [
            'bio_index'       => '(bio|bio/index)?',
            'language_prefix' => '\w{2}?',
        ],
    ],
    'bio_requests' => [
        'pattern'  => '/{language_prefix}/bio/requests',
        'defaults' => [
            '_controller' => 'Application\\Controller\\BioController::requests',
        ],
        'requirements' => [
            'language_prefix' => '\w{2}',
        ],
    ],
    // IndexController
    'gallery_list' => [
        'pattern'  => '/{language_prefix}/gallery{index_gallery}',
        'defaults' => [
            '_controller'   => 'Application\\Controller\\GalleryController::listItems',
            'index_gallery' => '',
        ],
        'requirements' => [
            'index_gallery'   => '(/index|/list)?',
            'language_prefix' => '\w{2}',
        ],
    ],
    'gallery_bytag' => [
        'pattern'  => '/{language_prefix}/gallery/bytag',
        'defaults' => [
            '_controller' => 'Application\\Controller\\GalleryController::bytag',
        ],
        'requirements' => [
            'language_prefix' => '\w{2}',
        ],
    ],
    'gallery_one_tag' => [
        'pattern'  => '/{language_prefix}/gallery/onetag/{tag}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\GalleryController::onetag',
            'tag'         => '',
        ],
        'requirements' => [
            'language_prefix' => '\w{2}',
        ],
    ],
    // AdminController
    'admin_login' => [
        'pattern'  => '/admin{index_login}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminController::login',
            'index_login' => '',
        ],
        'requirements' => [
            'index_login' => '(/index|/login)?'
        ],
    ],
    'admin_logout' => [
        'pattern'  => '/admin/logout',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminController::logout',
        ],
    ],
    // GalleryController
    'admin_gallery_list' => [
        'pattern'  => '/admin/gallery{index_list}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminGalleryController::listItems',
            'index_list'  => '',
        ],
        'requirements' => [
            'index_list' => '(/index|/list)?'
        ],
    ],
    'admin_gallery_add' => [
        'pattern'  => '/admin/gallery/add',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminGalleryController::add',
        ],
    ],
    'admin_gallery_crop' => [
        'pattern'  => '/admin/gallery/crop/{image}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminGalleryController::crop',
            'image'       => null,
        ],
    ],
    'admin_gallery_result' => [
        'pattern'  => '/admin/gallery/result/{image}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminGalleryController::result',
            'image'       => null,
        ],
    ],
    'admin_gallery_edit' => [
        'pattern'  => '/admin/gallery/edit/{id}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminGalleryController::edit',
            'id'          => null,
        ],
    ],
    'admin_gallery_editimage' => [
        'pattern'  => '/admin/gallery/editimage/{id}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminGalleryController::editimage',
            'id'          => null,
        ],
    ],
    'admin_gallery_delete' => [
        'pattern'  => '/admin/gallery/delete/{id}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminGalleryController::delete',
            'id'          => null,
        ],
    ],
    // TagController
    'admin_tag_list' => [
        'pattern'  => '/admin/tag{index_list}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminTagController::listItems',
            'index_list'  => '',
        ],
        'requirements' => [
            'index_list' => '(/index|/list)?'
        ],
    ],
    'admin_tag_add' => [
        'pattern'  => '/admin/tag/add',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminTagController::add',
        ],
    ],
    'admin_tag_edit' => [
        'pattern'  => '/admin/tag/edit/{id}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminTagController::edit',
            'id'          => null,
        ],
    ],
    'admin_tag_delete' => [
        'pattern'  => '/admin/tag/delete/{id}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminTagController::delete',
            'id'          => null,
        ],
    ],
];