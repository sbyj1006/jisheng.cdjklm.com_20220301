<?php

return array (
  0 => 
  array (
    'name' => 'app_id',
    'title' => 'app_id',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => 'wx64aa6c0b36818a52',
    'rule' => 'required',
    'msg' => '',
    'tip' => '你的微信公众号appid',
    'ok' => '',
    'extend' => '',
  ),
  1 => 
  array (
    'name' => 'secret',
    'title' => 'secret',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => 'ef8f08f9f771b0105f27d52afeed3a60',
    'rule' => 'required',
    'msg' => '',
    'tip' => '你的微信公众号appsecret',
    'ok' => '',
    'extend' => '',
  ),
  2 => 
  array (
    'name' => 'token',
    'title' => 'token',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => 'fzgj',
    'rule' => 'required',
    'msg' => '',
    'tip' => '通信token',
    'ok' => '',
    'extend' => '',
  ),
  3 => 
  array (
    'name' => 'aes_key',
    'title' => 'aes_key',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => '5JSi4cXUgeRRF2kT9Nwj2yNmuAlrdTsx3Iyf7HQa4pK',
    'rule' => '',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  4 => 
  array (
    'name' => 'debug',
    'title' => '调试模式',
    'type' => 'radio',
    'content' => 
    array (
      0 => '否',
      1 => '是',
    ),
    'value' => '0',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  5 => 
  array (
    'name' => 'log_level',
    'title' => '日志记录等级',
    'type' => 'select',
    'content' => 
    array (
      'debug' => 'debug',
      'info' => 'info',
      'notice' => 'notice',
      'warning' => 'warning',
      'error' => 'error',
      'critical' => 'critical',
      'alert' => 'alert',
      'emergency' => 'emergency',
    ),
    'value' => 'debug',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  6 => 
  array (
    'name' => 'oauth_callback',
    'title' => '登录回调',
    'type' => 'string',
    'content' => 
    array (
    ),
    'value' => 'https://www.jeasum.com//addons/wechat/index/api',
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
);
