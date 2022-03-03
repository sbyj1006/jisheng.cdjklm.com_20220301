<?php

return array (
  'autoload' => false,
  'hooks' => 
  array (
    'config_init' => 
    array (
      0 => 'nkeditor',
    ),
  ),
  'route' => 
  array (
    '/example$' => 'example/index/index',
    '/example/d/[:name]' => 'example/demo/index',
    '/example/d1/[:name]' => 'example/demo/demo1',
    '/example/d2/[:name]' => 'example/demo/demo2',
  ),
);