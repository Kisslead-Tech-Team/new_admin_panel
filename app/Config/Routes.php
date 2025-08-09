<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Workflow\LoginLogout;

$routes->get('/', 'BaseAction::index');
$routes->get('dashboard', 'BaseAction::dashboard');

# Product

# Product Type

# Product Category

# Product image



# Pet
$routes->get('tools-brand', 'BaseAction::toolsBrand');

#Brand


#Breed
$routes->get('breed', 'BaseAction::breed');




###########################[ API Routes ]##################################

# Login Logout
$routes->post('login-check', 'Workflow_LoginLogout::logincheck');
$routes->get('logout', 'Workflow_LoginLogout::logout');

# User
$routes->get('getusercount', 'Workflow_User::getUserCount');

# Pet
$routes->get('getbrand', 'Workflow_ToolsBrand::getBrand');
$routes->post('getspecificbrand', 'Workflow_ToolsBrand::getSpecificBrand');
$routes->post('insertbrand', 'Workflow_ToolsBrand::insertBrand');
$routes->post('updatebrand', 'Workflow_ToolsBrand::updateBrand');
$routes->post('deletebrand', 'Workflow_ToolsBrand::deleteBrand');


# Brand
$routes->get('getbreed', 'Workflow_Breed::getBreed');
// $routes->post('getspecificproductcategory', 'Workflow_Product_Category::getspecificProductCategory');
$routes->post('insertbreed', 'Workflow_Breed::insertBreed');
$routes->post('updatebreed', 'Workflow_Breed::updateBreed');
$routes->post('deletebreed', 'Workflow_Breed::deleteBreed');

