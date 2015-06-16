<?php

$config            = include_once 'base.php';
$config_additional = [
    'environment' => 'production',
];

return array_replace_recursive($config, $config_additional);
