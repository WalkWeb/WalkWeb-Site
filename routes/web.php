<?php

$routes = new WalkWeb\NW\Route\RouteCollection();

$routes->get('home', '/', 'App\\Handler\\MainHandler');
$routes->get('account.page', '/u/{name}', 'App\\Handler\\Account\\AccountPageHandler', ['name' => '[a-zA-Z0-9а-яА-ЯёЁ]+']);
$routes->get('account.registration.page', '/registration/{ref}', 'App\\Handler\\Account\\AccountRegistrationPageHandler', ['ref' => '[a-z0-9]+']);
$routes->post('account.registration', '/registration/{ref}', 'App\\Handler\\Account\\AccountRegistrationHandler', ['ref' => '[a-z0-9]+']);
$routes->get('account.login', '/login', 'App\\Handler\\Account\\AccountLoginPageHandler');
$routes->post('account.login', '/login', 'App\\Handler\\Account\\AccountLoginHandler');
$routes->get('account.verified.email', '/verified/email', 'App\\Handler\\Account\\VerifiedEmailPageHandler');
$routes->get('account.notice.list', '/notices/{page}', 'App\\Handler\\Account\\Notice\\AccountNoticePageHandler', ['page' => '\d+']);

$routes->get('statistic', '/statistic', 'App\\Handler\\StatisticHandler');

$routes
    ->addMiddleware('App\\Middleware\\StatisticsMiddleware')
    ->addMiddleware('App\\Middleware\\AuthMiddleware')
;

return new WalkWeb\NW\Route\Router($routes);
