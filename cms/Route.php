<?php

use Engine\Core\Router\Route;
use Engine\Helper\HelperDI;

$route = new Route(HelperDI::di());

/** MAIN NAVIGATION */
$route->get('/home', 'Home/HomeController:home', 'PAGE');
$route->get('/catalog','Catalog/CatalogController:catalog', 'PAGE');
$route->get('/profile', 'Account/ProfileController:profile', 'PAGE');
$route->get('/profile.orders', 'Account/ProfileController:orders', 'PAGE');
/** MAIN NAVIGATION */

/** HOME */
$route->get('/', 'IndexController:index', 'PAGE');
$route->get('/index', 'IndexController:index', 'PAGE');
/** HOME */

/** CMS */
$route->mixed('/engineeringWorks', 'CmsController:engineeringWorks', 'PAGE');
/** CMS */

/** ERROR */
$route->get('/error', 'ErrorController:error', 'PAGE');
/** ERROR */

/** ACCOUNT */
$route->get('/sigIn', 'Account/AccountController:sigIn', 'PAGE');
$route->get('/signUp', 'Account/RegistrationController:signUp', 'PAGE');


$route->mixed('/SMSVerification.sendSMS', 'Account/RegistrationController:sendSMS', 'DATA');
$route->mixed('/SMSVerification.checkSMSCode', 'Account/RegistrationController:checkSMSCode', 'DATA');

$route->mixed('/method/WeChatCallback', 'Account/RegistrationController:WeChatCallback', 'DATA');
/** ACCOUNT */

/** CUSTOMER */
$route->get('/method/customer.registration', 'Customer/CustomerController:registration', 'DATA');
/** CUSTOMER */


/** SHOP */
$route->get('/method/order.create', 'Shop/OrderController:create', 'DATA');
$route->get('/page/cart.checkout', 'Shop/CartController:checkout', 'DATA');
$route->get('/method/cart.getCheckoutData', 'Shop/CartController:getCheckout', 'PAGE');
$route->get('/method/cart.count', 'Shop/CartController:count', 'DATA');
$route->post('/method/cart.add', 'Shop/CartController:add', 'DATA');
$route->get('/method/cart.remove', 'Shop/CartController:remove', 'DATA');
$route->get('/method/cart.quantityInc', 'Shop/CartController:quantityInc', 'DATA');
$route->get('/method/cart.quantityDec', 'Shop/CartController:quantityDec', 'DATA');
/** SHOP */

/** CATEGORY */
$route->get('/method/category.getAll', 'Catalog/Category/CategoryController:getAll', 'DATA');
$route->get('/method/category.navigate', 'Catalog/Category/CategoryController:navigate', 'DATA');
/** CATEGORY */

/** CATALOG */
// todo: dog nail
$route->get('/product/details/(product_id:int)', 'Catalog/Product/ProductController:d', 'PAGE');

$route->get('/page/product.list', 'Catalog/Product/ProductController:list', 'PAGE');
$route->get('/page/product.details', 'Catalog/Product/ProductController:details', 'PAGE');
$route->get('/method/product.getRows', 'Catalog/Product/ProductController:getRows', 'DATA');
$route->get('/method/product.getProducts', 'Catalog/Product/ProductController:getProducts', 'DATA');
/** CATALOG */





