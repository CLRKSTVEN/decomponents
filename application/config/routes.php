<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Landing page = Provincial controller
$route['default_controller'] = 'pages/home';

// keep these for admin login
$route['login']      = 'login';
$route['login/auth'] = 'login/auth';
$route['home_page.php'] = 'login';
$route['home_page']     = 'login';

// Provincial routes
$route['provincial']           = 'provincial/index';      // optional
$route['provincial/standings'] = 'provincial/index';      // same landing
$route['provincial/admin']     = 'provincial/admin';

$route['home']                = 'pages/home';
$route['products']            = 'pages/products';
$route['about']               = 'pages/about';
$route['about-more']          = 'pages/about_more';
$route['tradables']           = 'pages/tradables';
$route['trade-now']           = 'pages/trade_now';
$route['trading']             = 'pages/trading';
$route['ordering']            = 'pages/ordering';
$route['notification']        = 'pages/notification';
$route['order-notification']  = 'pages/order_notification';
$route['news']                = 'pages/news';
$route['contact']             = 'pages/contact';
$route['test']                = 'pages/test';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['provincial/update-settings'] = 'provincial/update_meet_settings';
