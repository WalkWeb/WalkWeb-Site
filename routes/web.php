<?php

$routes = new WalkWeb\NW\Route\RouteCollection();

$routes->get('home', '/', 'App\\Handler\\MainHandler');
$routes->get('account.page', '/u/{name}', 'App\\Handler\\Account\\AccountPageHandler', ['name' => '[a-zA-Z0-9а-яА-ЯёЁ]+']);

return new WalkWeb\NW\Route\Router($routes);
