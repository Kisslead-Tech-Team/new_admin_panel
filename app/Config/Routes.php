<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Workflow\LoginLogout;

$routes->get('/', 'BaseAction::index');
$routes->get('dashboard', 'BaseAction::dashboard');

# Product

# Product Type

# Product Category

# Product image



# Tools Brand
$routes->get('tools-brand', 'BaseAction::toolsBrand');
$routes->get('tools-category', 'BaseAction::toolsCategory');
$routes->get('tools', 'BaseAction::tools');
$routes->get('gallery', 'BaseAction::gallery');
$routes->get('youtube', 'BaseAction::youtube');
$routes->get('enquiries', 'BaseAction::enquiries');








###########################[ API Routes ]##################################

# Login Logout
$routes->post('login-check', 'Workflow_LoginLogout::logincheck');
$routes->get('logout', 'Workflow_LoginLogout::logout');

# User
$routes->get('getusercount', 'Workflow_User::getUserCount');

# Brand
$routes->get('getbrand', 'Workflow_ToolsBrand::getBrand');
$routes->get('getoptionbrand', 'Workflow_ToolsBrand::getBrandOption');
$routes->post('insertbrand', 'Workflow_ToolsBrand::insertBrand');
$routes->post('updatebrand', 'Workflow_ToolsBrand::updateBrand');
$routes->post('deletebrand', 'Workflow_ToolsBrand::deleteBrand');

# Category
$routes->get('getcategory', 'Workflow_ToolsCategory::getCategory');
$routes->get('getoptioncategory', 'Workflow_ToolsCategory::getCategoryOption');
$routes->post('insertcategory', 'Workflow_ToolsCategory::insertCategory');
$routes->post('updatecategory', 'Workflow_ToolsCategory::updateCategory');
$routes->post('deletecategory', 'Workflow_ToolsCategory::deleteCategory');

# Tools
$routes->get('gettools', 'Workflow_Tools::getTools');
$routes->post('inserttools', 'Workflow_Tools::insertTools');
$routes->post('updatetools', 'Workflow_Tools::updateTools');
$routes->post('deleteimagetools', 'Workflow_Tools::deleteToolsImage');
$routes->post('deletetools', 'Workflow_Tools::deleteTools');

# Gallery
$routes->get('getgallery', 'Workflow_Gallery::getGallery');
$routes->post('insertgallery', 'Workflow_Gallery::insertGallery');
$routes->post('updategallery', 'Workflow_Gallery::updateGallery');
$routes->post('deletegallery', 'Workflow_Gallery::deleteGallery');

# youtube
$routes->get('getyoutube', 'Workflow_Youtube::getYoutube');
$routes->post('insertyoutube', 'Workflow_Youtube::insertYoutube');
$routes->post('updateyoutube', 'Workflow_Youtube::updateYoutube');
$routes->post('deleteyoutube', 'Workflow_Youtube::deleteYoutube');

# enquiries
$routes->get('getenquiries', 'Workflow_Enquiries::getEnquiries');
$routes->post('deleteenquiries', 'Workflow_Enquiries::deleteEnquiries');