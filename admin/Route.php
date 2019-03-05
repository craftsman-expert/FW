<?php

use Engine\Core\Router\Route;

use Engine\Helper\HelperDI;

$route = new Route(HelperDI::di());

$route->get('/admin/login.form', 'LoginController:form',  'PAGE');
$route->post('/admin/login',     'LoginController:login', 'DATA');
$route->get('/admin/logout',     'LoginController:logout','DATA');

$route->get('/admin/',           'DashboardController:admin', 'PAGE');
$route->get('/admin/dashboard',  'DashboardController:dashboard', 'PAGE');

$route->get('/admin/page/file.manager','FileManagerController:manager');
$route->mixed('/admin/data/file.manager.connector','FileManagerController:connector');



/** Order */
$route->get('/admin/page/order.orderManager', 'Sale/Order/OrderController:orderManager', 'PAGE');
$route->get('/admin/page/order.orderDetails', 'Sale/Order/OrderController:orderDetails', 'PAGE');

$route->mixed('/admin/method/order.getRows', 'Sale/Order/OrderController:getRows', 'DATA');
$route->mixed('/admin/method/order.delete', 'Sale/Order/OrderController:delete', 'DATA');
/** Order */

/** Product */
$route->get('/admin/page/product.list', 'Catalog/Product/ProductController:list', 'PAGE');
$route->get('/admin/method/product.getRows', 'Catalog/Product/ProductController:getRows', 'DATA');
$route->get('/admin/page/product.new', 'Catalog/Product/ProductController:new');
$route->get('/admin/page/product.edit', 'Catalog/Product/ProductController:edit');
$route->mixed('/admin/method/product.add', 'Catalog/Product/ProductController:add', 'DATA');
$route->mixed('/admin/method/product.update', 'Catalog/Product/ProductController:update', 'DATA');
$route->mixed('/admin/method/product.delete', 'Catalog/Product/ProductController:delete', 'DATA');
/** Product */

/** Category */
$route->get('/admin/page/category.list', 'Catalog/Category/CategoryController:list');
$route->post('/admin/method/category.add', 'Catalog/Category/CategoryController:add', 'DATA');
$route->post('/admin/method/category.update', 'Catalog/Category/CategoryController:update', 'DATA');
$route->get('/admin/method/category.lookup', 'Catalog/Category/CategoryController:lookup', 'DATA');
$route->get('/admin/method/category.delete', 'Catalog/Category/CategoryController:delete', 'DATA');
$route->get('/admin/method/category.getRow', 'Catalog/Category/CategoryController:getRow', 'DATA');
$route->get('/admin/method/category.getAll', 'Catalog/Category/CategoryController:getAll', 'DATA');
/** Category */


