<?php

$routes = new WalkWeb\NW\Route\RouteCollection();

$routes->get('home', '/', 'App\\Handler\\MainHandler');

return new WalkWeb\NW\Route\Router($routes);
