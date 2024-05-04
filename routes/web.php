<?php

$routes = new WalkWeb\NW\Route\RouteCollection();

$routes->get('home', '/', 'MainHandler');

return new WalkWeb\NW\Route\Router($routes);
