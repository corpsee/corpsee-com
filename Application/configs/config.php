<?php

return [
    // production, test, debug
    'environment'      => 'debug',
    'timezone'         => 'Asia/Novosibirsk',
    'locale'           => 'ru',
    'language'         => 'ru',
    'error_controller' => 'Application\\Controller\\ErrorController::error',
    'modules'          => ['Logger', 'Assets', 'Database', 'Validation', 'Auth', 'Mailer'],
    'assets'           => [
        'lessjs_url' => FILE_PATH_URL . 'lib/less/1.7.5/less.min.js',
    ],
    'assets.packages' => include_once 'assets.php',
    'database' => [
        'type' => 'sqlite',
        'dns'  => 'sqlite:' . APPLICATION_PATH . 'corpsee.sqlite',
    ],
    'logger' => [
        'name' => 'corpsee.local',
    ],
    'validation' => [
        'rules' => [
            'GalleryForm' => [
                'title'       => ['noempty'],
                'description' => ['noempty'],
                'tags'        => ['noempty'],
                'create_date' => ['noempty'],
            ],
            'TagForm' => [
                'tag' => ['noempty'],
            ],
            'UserForm' => [
                'login'    => ['noempty'],
                'email'    => ['noempty', 'email'],
                'password' => ['noempty', ['min_length', 6]],
            ],
        ],
    ],
    'auth' => [
        'access' => [
            'Application\\Controller\\AdminGalleryController' => [
                'listItems' => ['ROLE_REGISTERED'],
                'add'       => ['ROLE_ADMIN'],
                'crop'      => ['ROLE_ADMIN'],
                'result'    => ['ROLE_ADMIN'],
                'edit'      => ['ROLE_ADMIN'],
                'editimage' => ['ROLE_ADMIN'],
                'delete'    => ['ROLE_ADMIN']
            ],
            'Application\\Controller\\AdminTagController' => [
                'listItems' => ['ROLE_REGISTERED'],
                'add'       => ['ROLE_ADMIN'],
                'edit'      => ['ROLE_ADMIN'],
                'delete'    => ['ROLE_ADMIN']
            ],
        ],
        'users' => [
            'corpsee' => [
                'password' => '$2a$08$THJnQkdscWJoTTRLM1dhROYLSMHrCV4JzWQksIujQjCT1cq25OxxG',
                'groups'   => [
                    'ROLE_ADMIN',
                    'ROLE_REGISTERED',
                ],
            ],
            'registered' => [
                'password' => '$2a$08$bEJzRGI4TDFmNzN4SjdoUObn292Fg8/yEohQCWDTLsWP1Qkj0OmeG',
                'groups'   => [
                    'ROLE_REGISTERED',
                ],
            ],
        ],
    ],
    'routes' => include_once 'routes.php',
    'isset_languages' => [
        'ru' => ['ru', 'be', 'uk'],
        'en' => ['en'],
    ],
];