<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->post('/login', 'Home::login');
$routes->get('/salir', 'Home::salir');
$routes->get('/reporte', 'Home::reporte');

$routes->get('/inicio', 'Inicio::index');
$routes->get('/inicio/add', 'Inicio::add');
$routes->post('/inicio/add_create', 'Inicio::add_create');
$routes->put('/inicio/edit/(:any)', 'Inicio::edit/$1');
$routes->post('/inicio/actualizar', 'Inicio::actualizar');
$routes->post('/inicio/actadd', 'Inicio::actadd');
$routes->delete('/inicio/delete/(:any)', 'Inicio::delete/$1');
$routes->get('/inicio/view/(:any)', 'Inicio::view/$1');
$routes->delete('/inicio/dser/(:any)', 'Inicio::dser/$1');

//$routes->get('product/(:any)', 'Catalog::productLookup/$1');
// $routes->post('products', 'Product::feature');
// $routes->put('products/1', 'Product::feature');
// $routes->delete('products/1', 'Product::feature');



/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
