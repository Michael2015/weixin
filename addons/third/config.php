<?php

return array (
  0 => 
  array (
    'name' => 'qq',
    'title' => 'QQ',
    'type' => 'array',
    'content' => 
    array (
      'app_id' => '',
      'app_secret' => '',
      'scope' => 'get_user_info',
    ),
    'value' => 
    array (
      'app_id' => '100000000',
      'app_secret' => '123456',
      'scope' => 'get_user_info',
    ),
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  1 => 
  array (
    'name' => 'wechat',
    'title' => '微信',
    'type' => 'array',
    'content' => 
    array (
      'app_id' => '',
      'app_secret' => '',
      'callback' => '',
      'scope' => 'snsapi_base',
    ),
    'value' => 
    array (
      'app_id' => 'wxd3bef165f40c217a',
      'app_secret' => '7c80d3c3d4e9495f420543b4a15d580d',
      'scope' => 'snsapi_base',
    ),
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  2 => 
  array (
    'name' => 'weibo',
    'title' => '微博',
    'type' => 'array',
    'content' => 
    array (
      'app_id' => '',
      'app_secret' => '',
      'scope' => 'get_user_info',
    ),
    'value' => 
    array (
      'app_id' => '2018728792',
      'app_secret' => 'e2dadd7a6b1f39dccf859e34038d4637',
      'scope' => 'get_user_info',
    ),
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
  3 => 
  array (
    'name' => 'rewrite',
    'title' => '伪静态',
    'type' => 'array',
    'content' => 
    array (
    ),
    'value' => 
    array (
      'index/index' => '/third$',
      'index/connect' => '/third/connect/[:platform]',
      'index/callback' => '/third/callback/[:platform]',
    ),
    'rule' => 'required',
    'msg' => '',
    'tip' => '',
    'ok' => '',
    'extend' => '',
  ),
);
