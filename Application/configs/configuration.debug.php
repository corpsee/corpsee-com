<?php

return array
(
    // production, test, debug
    'environment'      => 'debug',
    'timezone'         => 'Asia/Novosibirsk',
    'locale'           => 'ru',
    'language'         => 'ru',
    'error_controller' => 'Application\\Controller\\ErrorController::error',
    'modules'          => array('Logger', 'Assets', 'Database', 'Validation', 'Auto', 'Mailer'),
    'assets'           => array
    (
        'lessjs_url' => FILE_PATH_URL . 'lib/less/1.7.5/less.min.js',
    ),
    'assets.packages' => include_once 'assets.php',
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