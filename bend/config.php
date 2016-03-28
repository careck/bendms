<?php

Config::set('bend', array(
    'active' => true,
    'path' => 'modules',
    'topmenu' => true,
    'search' => array(
        "Example Data" => "ExampleData",
    ),
    'widgets' => array(),
    'hooks' => array('core_dbobject', 'core_web','bend'),
    'processors' => array(),
));

