<?php

$routes = new WalkWeb\NW\Route\RouteCollection();

$routes->get('home', '/', 'App\\Handler\\MainHandler');
$routes->get('account.page', '/u/{name}', 'App\\Handler\\Account\\AccountPageHandler', ['name' => '[a-zA-Z0-9а-яА-ЯёЁ]+']);
$routes->get('account.registration.page', '/registration/{ref}', 'App\\Handler\\Account\\AccountRegistrationPageHandler', ['ref' => '[a-z0-9]+']);
$routes->post('account.registration', '/registration/{ref}', 'App\\Handler\\Account\\AccountRegistrationHandler', ['ref' => '[a-z0-9]+']);
$routes->get('account.login', '/login', 'App\\Handler\\Account\\AccountLoginPageHandler');
$routes->post('account.login', '/login', 'App\\Handler\\Account\\AccountLoginHandler');
$routes->get('account.logout', '/logout', 'App\\Handler\\Account\\AccountLogoutHandler');
$routes->get('account.verified.email', '/verified/email', 'App\\Handler\\Account\\VerifiedEmailPageHandler');
$routes->get('account.check.email', '/check/email/{token}', 'App\\Handler\\Account\\AccountCheckMailHandler', ['token' => '[a-zA-Z0-9-]+']);
$routes->get('account.notice.list', '/notices/{page}', 'App\\Handler\\Account\\Notice\\AccountNoticePageHandler', ['page' => '\d+']);
$routes->post('account.notice.close', '/notice/close/{id}', 'App\\Handler\\Account\\Notice\\NoticeCloseHandler', ['id' => '[a-zA-Z0-9-]+']);
$routes->post('account.notice.close.all', '/notice/all/close', 'App\\Handler\\Account\\Notice\\NoticeCloseAllHandler');
$routes->get('account.banned', '/banned', 'App\\Handler\\Account\\AccountBannedPageHandler');
$routes->get('account.profile', '/profile', 'App\\Handler\\Account\\Profile\\ProfilePageHandler');
$routes->get('account.list', '/users/{page}', 'App\\Handler\\Account\\AccountListPageHandler', ['page' => '\d+']);

$routes->get('character.get', '/c/{id}', 'App\\Handler\\Character\\CharacterPageHandler', ['id' => '[a-zA-Z0-9-]+']);

$routes->get('post.get', '/p/{slug}', 'App\\Handler\\Post\\PostPageHandler', ['slug' => '[a-zA-Z0-9-]+']);
$routes->post('post.get', '/post/like/{slug}', 'App\\Handler\\Post\\LikePostHandler', ['slug' => '[a-zA-Z0-9-]+']);

$routes->get('statistic', '/statistic', 'App\\Handler\\StatisticPageHandler');

// TODO temporary methods
$routes->get('account.add.exp', '/add/exp', 'App\\Handler\\Temporary\\AddExpHandler');
$routes->post('account.reduced.energy', '/reduced/energy', 'App\\Handler\\Temporary\\ReducedEnergyHandler');

$routes
    ->addMiddleware('App\\Middleware\\StatisticsMiddleware', 10)
    ->addMiddleware('App\\Middleware\\AuthMiddleware', 100)
;

return new WalkWeb\NW\Route\Router($routes);
