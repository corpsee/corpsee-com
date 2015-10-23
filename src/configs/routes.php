<?php

return [
    // ErrorController
    'error' => [
        'path'  => '/error/{code}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\ErrorController::errorServer',
            'code'        => 500,
        ],
        'requirements' => [
            'code' => '\d{3}',
        ],
    ],
    'admin_error' => [
        'path'  => '/admin/error/{code}',
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
        'path'  => '/{language_prefix}/{bio_index}',
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
        'path'  => '/{language_prefix}/bio/requests/{year}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\BioController::requests',
            'year'        => null,
        ],
        'requirements' => [
            'language_prefix' => '\w{2}',
            'year'            => '\d{4}',
        ],
    ],
    // IndexController
    'gallery_list' => [
        'path'  => '/{language_prefix}/gallery{index_gallery}',
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
        'path'  => '/{language_prefix}/gallery/bytag',
        'defaults' => [
            '_controller' => 'Application\\Controller\\GalleryController::byTag',
        ],
        'requirements' => [
            'language_prefix' => '\w{2}',
        ],
    ],
    'gallery_one_tag' => [
        'path'  => '/{language_prefix}/gallery/onetag/{tag}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\GalleryController::oneTag',
            'tag'         => '',
        ],
        'requirements' => [
            'language_prefix' => '\w{2}',
        ],
    ],
    // AdminController
    'admin_login' => [
        'path'  => '/admin{index_login}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminController::login',
            'index_login' => '',
        ],
        'requirements' => [
            'index_login' => '(/index|/login)?'
        ],
    ],
    'admin_logout' => [
        'path'  => '/admin/logout',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminController::logout',
        ],
    ],
    // GalleryController
    'admin_gallery_list' => [
        'path'  => '/admin/gallery{index_list}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminGalleryController::listItems',
            'index_list'  => '',
        ],
        'requirements' => [
            'index_list' => '(/index|/list)?'
        ],
    ],
    'admin_gallery_add' => [
        'path'  => '/admin/gallery/add',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminGalleryController::add',
        ],
    ],
    'admin_gallery_crop' => [
        'path'  => '/admin/gallery/crop/{image}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminGalleryController::crop',
            'image'       => null,
        ],
    ],
    'admin_gallery_result' => [
        'path'  => '/admin/gallery/result/{image}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminGalleryController::result',
            'image'       => null,
        ],
    ],
    'admin_gallery_edit' => [
        'path'  => '/admin/gallery/edit/{id}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminGalleryController::edit',
            'id'          => null,
        ],
    ],
    'admin_gallery_editimage' => [
        'path'  => '/admin/gallery/editimage/{id}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminGalleryController::editimage',
            'id'          => null,
        ],
    ],
    'admin_gallery_delete' => [
        'path'  => '/admin/gallery/delete/{id}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminGalleryController::delete',
            'id'          => null,
        ],
    ],
    // TagController
    'admin_tag_list' => [
        'path'  => '/admin/tag{index_list}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminTagController::listItems',
            'index_list'  => '',
        ],
        'requirements' => [
            'index_list' => '(/index|/list)?'
        ],
    ],
    'admin_tag_add' => [
        'path'  => '/admin/tag/add',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminTagController::add',
        ],
    ],
    'admin_tag_edit' => [
        'path'  => '/admin/tag/edit/{id}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminTagController::edit',
            'id'          => null,
        ],
    ],
    'admin_tag_delete' => [
        'path'  => '/admin/tag/delete/{id}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminTagController::delete',
            'id'          => null,
        ],
    ],
    // ProjectController
    'admin_project_list' => [
        'path'  => '/admin/project{index_list}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminProjectController::listItems',
            'index_list'  => '',
        ],
        'requirements' => [
            'index_list' => '(/index|/list)?'
        ],
    ],
    'admin_project_add' => [
        'path'  => '/admin/project/add',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminProjectController::add',
        ],
    ],
    'admin_project_edit' => [
        'path'  => '/admin/project/edit/{id}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminProjectController::edit',
            'id'          => null,
        ],
    ],
    'admin_project_delete' => [
        'path'  => '/admin/project/delete/{id}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminProjectController::delete',
            'id'          => null,
        ],
    ],
    // PullRequestController
    'admin_pull_request_list' => [
        'path'  => '/admin/pull_request{index_list}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminPullRequestController::listItems',
            'index_list'  => '',
        ],
        'requirements' => [
            'index_list' => '(/index|/list)?'
        ],
    ],
    'admin_pull_request_edit' => [
        'path'  => '/admin/pull_request/edit/{id}',
        'defaults' => [
            '_controller' => 'Application\\Controller\\AdminPullRequestController::edit',
            'id'          => null,
        ],
    ],
];
