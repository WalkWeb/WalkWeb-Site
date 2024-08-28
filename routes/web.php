<?php

$routes = new WalkWeb\NW\Route\RouteCollection();

// user
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
$routes->get('character.get', '/h/{id}', 'App\\Handler\\Character\\CharacterPageHandler', ['id' => '[a-zA-Z0-9-]+']);

// post
$routes->get('post.get', '/p/{slug}', 'App\\Handler\\Post\\PostPageHandler', ['slug' => '[a-zA-Z0-9-]+']);
$routes->post('post.like', '/post/like/{slug}', 'App\\Handler\\Post\\LikePostHandler', ['slug' => '[a-zA-Z0-9-]+']);
$routes->post('post.dislike', '/post/dislike/{slug}', 'App\\Handler\\Post\\DislikePostHandler', ['slug' => '[a-zA-Z0-9-]+']);
$routes->get('post.create', '/post/create', 'App\\Handler\\Post\\CreatePostPageHandler');
$routes->post('post.create', '/post/create', 'App\\Handler\\Post\\CreatePostHandler');
$routes->post('image.upload.json', '/image/upload/json', 'App\\Handler\\Image\\UploadImageHandler');

// comment
$routes->post('comment.create', '/comment/create', 'App\\Handler\\Comment\\CreateCommentHandler');
$routes->post('comment.like', '/comment/like/{id}', 'App\\Handler\\Comment\\LikeCommentHandler', ['id' => '[a-zA-Z0-9-]+']);
$routes->post('comment.dislike', '/comment/dislike/{id}', 'App\\Handler\\Comment\\DislikeCommentHandler', ['id' => '[a-zA-Z0-9-]+']);

// tag
$routes->get('tag.list', '/t/{slug}/{rating}', 'App\\Handler\\Tag\\TagPageHandler', ['slug' => '[a-zA-Z0-9-]+', 'rating' => '[a-z]+']);

// info
$routes->get('statistic', '/statistic', 'App\\Handler\\Info\\StatisticPageHandler');
$routes->get('functionality', '/functionality', 'App\\Handler\\Info\\FunctionalityPageHandler');
$routes->get('rules', '/rules', 'App\\Handler\\Info\\RulesPageHandler');

// ratings
$routes->get('top.account.level', '/top/account/level', 'App\\Handler\\Rating\\AccountLevelRatingPageHandler');
$routes->get('top.account.carma', '/top/account/carma', 'App\\Handler\\Rating\\AccountCarmaRatingPageHandler');
$routes->get('top.account.genesis', '/top/account/genesis', 'App\\Handler\\Rating\\GenesisRatingPageHandler');

// community
$routes->get('community.list', '/community/{page}', 'App\\Handler\\Community\\CommunityListPageHandler', ['page' => '\d+']);
$routes->get('community.get', '/c/{slug}', 'App\\Handler\\Community\\CommunityPageHandler', ['slug' => '[a-zA-Z0-9-]+']);
$routes->post('community.join', '/community/join/{slug}', 'App\\Handler\\Community\\JoinCommunityHandler', ['slug' => '[a-zA-Z0-9-]+']);
$routes->post('community.leave', '/community/leave/{slug}', 'App\\Handler\\Community\\LeaveCommunityHandler', ['slug' => '[a-zA-Z0-9-]+']);

// admin panel
$routes->get('panel.index', '/panel', 'App\\Handler\\Panel\\PanelPageHandler');

// temporary methods
// TODO delete
$routes->get('account.add.exp', '/add/exp', 'App\\Handler\\Temporary\\AddExpHandler');
$routes->post('account.reduced.energy', '/reduced/energy', 'App\\Handler\\Temporary\\ReducedEnergyHandler');

$routes
    ->addMiddleware('App\\Middleware\\StatisticsMiddleware', 10)
    ->addMiddleware('App\\Middleware\\AuthMiddleware', 100)
;

return new WalkWeb\NW\Route\Router($routes);
