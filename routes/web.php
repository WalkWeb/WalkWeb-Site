<?php

$routes = new WalkWeb\NW\Route\RouteCollection();

$routes->get('home', '/', 'App\\Handler\\MainHandler');
$routes->get('account.page', '/u/{name}', 'App\\Handler\\Account\\AccountPageHandler', ['name' => '[a-zA-Z0-9а-яА-ЯёЁ]+']);
$routes->get('account.registration.page', '/registration', 'App\\Handler\\Account\\AccountRegistrationPageHandler');

return new WalkWeb\NW\Route\Router($routes);
