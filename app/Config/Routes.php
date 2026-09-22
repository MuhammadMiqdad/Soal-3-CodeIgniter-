<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Products::index');

$routes->get('/products', 'Products::index');
$routes->get('/products/new', 'Products::new');
$routes->post('/products/create', 'Products::create');
$routes->get('/products/edit/(:num)', 'Products::edit/$1');
$routes->post('/products/update/(:num)', 'Products::update/$1');
$routes->get('/products/delete/(:num)', 'Products::delete/$1');
$routes->get('/products/buy/(:num)', 'Products::buy/$1');
$routes->post('/products/processBuy/(:num)', 'Products::processBuy/$1');

$routes->get('/categories', 'Categories::index');
$routes->get('/categories/new', 'Categories::new');
$routes->post('/categories/create', 'Categories::create');
$routes->get('/categories/edit/(:num)', 'Categories::edit/$1');
$routes->post('/categories/update/(:num)', 'Categories::update/$1');
$routes->get('/categories/delete/(:num)', 'Categories::delete/$1');