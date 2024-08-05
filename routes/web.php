<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\BankBranch;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return redirect('/dashboard');
// });

Route::get('/', 'HomeController@index')->name('home');

Route::get('/download/employee-upload-template', function () {
    return Storage::disk('public')->download('templates/employee_bulk_import.csv');
})->name('download-upload-employee-template');

Route::get('/download/farmers-upload-template', function () {
    return Storage::disk('public')->download('templates/farmers_bulk_import.csv');
})->name('download-upload-farmers-template');

Route::get('/download/{link}', function ($link) {
    return Storage::disk('public')->download($link);
})->name('download.files');

//Route::get("/tests", "TestsController@tests");

Auth::routes(
    [
        'register' => false,
        'verify' => false
    ]
);

// view quotation
Route::get("/quotations/{id}", "Common\CommonController@view_quotation")
    ->name("common.view-quotation");
Route::get("/invoices/{id}", "Common\CommonController@view_invoice")
    ->name("common.view-invoice");
Route::get("/receipts/{id}", "Common\CommonController@view_receipt")
    ->name("common.view-receipt");

//load countries
Route::get("/countries/data", "FirstTimeConfig@save_country_details")
    ->name('countries-data');


Route::get('/admin/roles', 'RolesNPermissionsContoller@admin_roles');

Route::get('/old-dashboard', 'HomeController@coop_admin_dashboard')->name('old-dashboard');

Route::get('/dashboard-analytics', 'HomeController@dashboard_data')
    ->name('cooperative.dashboard');
Route::get('cooperative/vet/bookings_by_vet/{type}', 'VetController@get_vets_by_category')->name('cooperative.vets_by_category');
Route::post('farm/stages-by-crop/{crop_id}/{type}', 'CropManagementController@get_stages_by_crop')->name('cooperative.stages-by-crop');

Route::post('/bank/{bankId}/branches/get', function ($bankId) {
    return BankBranch::getByBankId($bankId);
})->name('bank_branches-by-bank');
Route::prefix('/')->group(function () {
    Route::get('register', 'Farmer\RegisterController@index')->name('farmer.register');
    Route::post('register', 'Farmer\RegisterController@register')->name('farmer.register');
    Route::get('cooperative/logistics/locations/search', 'TripController@locationSearch')->name('cooperative.logistics.location-search');
    Route::any('bank/{id}/branches', 'Farmer\RegisterController@bank_branches_by_bank')->name('bank-branches');
});

Route::get('/payment/{id}/receipt', 'Farmer\ProfileController@print_payment_reciept')->name('farmer-receipt');

//sub chief -> Lydia, Marama, lukoho
//3 documents -> succession, death certificate.

Route::get('/change-password', 'UserManagementController@change_password')->name('change-password');
Route::post('/update-password', 'UserManagementController@update_password')->name('update-password');

// common
Route::get('/common/collection/{collection_id}/unit', 'Common\CommonController@collection_unit')->name('collection.get-collection-unit');
Route::get('/common/product/{product_id}/unit', 'Common\CommonController@product_unit')->name('collection.get-product-unit');


// superadmin routes
Route::middleware('role:super-admin')->prefix("super-admin")->group(function () {
});

Route::prefix("chat")->group(function() {
    Route::get("/", 'ChatController@index')->name('chat.index');
    Route::post("/add-group", 'ChatController@add_group')->name('chat.add-group');
    Route::post("/send-message", 'ChatController@send_message')->name('chat.send-message');
    Route::post("/search-group-to-join", 'ChatController@search_group_to_join')->name('chat.search-group-to-join');
    Route::post("/add-group-member", 'ChatController@add_group_member')->name('chat.add-group-member');
    Route::post("/add-chat", 'ChatController@add_chat')->name('chat.add-chat');
});

//admin routes
Route::middleware('role:admin')->prefix("admin")->group(function () {
    Route::get('/dashboard', 'Admin\DashboardController@index')->name('admin.dashboard');
    Route::get('/optimize', "FirstTimeConfig@optimize_app")->name('optimize');

    Route::get('/cooperative/setup', 'CooperativeController@index')->name('cooperative');
    Route::post('/cooperative/setup', 'CooperativeController@add_company')->name('cooperative.setup');
    Route::get('/cooperative/setup/update/{id}', 'CooperativeController@view_edit_company')->name('cooperative.setup.view-update');
    Route::patch('/cooperative/setup/update', 'CooperativeController@edit_company')->name('cooperative.setup.update');
    Route::get('/cooperative/setup/delete/{id}', 'CooperativeController@delete_company')->name('cooperative.setup.delete');
    Route::get('/cooperative/setup/deactivate/{id}', 'CooperativeController@deactivate_company')->name('cooperative.setup.deactivate');
    Route::get('/cooperative/setup/activate/{id}', 'CooperativeController@activate_company')->name('cooperative.setup.activate');


    //branches
    Route::get('/branches', 'Admin\CoopBranchController@index')
        ->name('branches.show');
    Route::get('/branch/{id}', 'Admin\CoopBranchController@edit')
        ->name('branches.detail');
    Route::post('/branches/add', 'Admin\CoopBranchController@store')
        ->name('branches.add');
    Route::post('/branches/edit', 'Admin\CoopBranchController@update')
        ->name('branches.edit');
    Route::get('/branches/delete/{id}', 'Admin\CoopBranchController@delete')
        ->name('branches.delete');

    Route::get('/cooperative/payroll-config', 'CooperativeController@payroll_config')->name('cooperative.payroll-config');
    Route::post('/cooperative/payroll-config', 'CooperativeController@add_payroll_config')->name('cooperative.payroll-config.add');
    Route::post('/cooperative/payroll-config/{id}', 'CooperativeController@edit_payroll_config')->name('cooperative.payroll-config.edit');
    Route::post('/cooperative/payroll-config/{id}/delete', 'CooperativeController@delete_payroll_config')->name('cooperative.payroll-config.delete');
    Route::prefix('/manage')->group(function () {
        Route::get('/modules', 'SystemModuleController@index')->name('modules');
        Route::post('/module', 'SystemModuleController@store')->name('module.add');
        Route::post('/module/{id}', 'SystemModuleController@edit')->name('module.edit');
        Route::get('/sub-modules', 'SystemModuleController@subModule')->name('sub-modules');
        Route::post('/sub-modules', 'SystemModuleController@addSubmodules')->name('sub-modules.add');
    });

    // millers
    Route::get('/millers', 'Admin\MillersController@index')
        ->name('admin.millers.show');
    Route::post('/millers/add', 'Admin\MillersController@store')
        ->name('admin.millers.add');

    // miller branches
    Route::get('/miller-branches', 'Admin\MillerBranchesController@index')
        ->name('admin.miller-branches.show');
    Route::post('/miller-branches/add', 'Admin\MillerBranchesController@store')
        ->name('admin.miller-branches.add');

    // users
    Route::get('/users', 'Admin\UsersController@index')
        ->name('admin.users.show');
    Route::post('/users', 'Admin\UsersController@store')
        ->name('admin.users.add');
    Route::get('/users/{id}', 'Admin\UsersController@detail')
        ->name('admin.users.detail');
    Route::get('/users/make-employee/{id}', 'Admin\UsersController@viewMakeEmployee')
        ->name('admin.users.view-make-employee');
    Route::post('/users/make-employee/{id}', 'Admin\UsersController@makeEmployee')
        ->name('admin.users.make-employee');
    Route::get('/users/make-county-govt-official/{id}', 'Admin\UsersController@viewMakeCountyGovtAcc')
        ->name('admin.users.view-make-county-govt-official');
    Route::post('/users/make-employee', 'Admin\UsersController@makeCountyGovtAcc')
        ->name('admin.users.make-county-govt-official');
    Route::get('/users/edit/{id}', 'Admin\UsersController@edit')
        ->name('admin.users.edit');
    Route::post('/users/edit', 'Admin\UsersController@update')
        ->name('admin.users.update');
    // todo: implement activate/deactivate
    // todo: implement delete

    // employees
    Route::get('/employees', 'Admin\EmployeesController@index')
        ->name('admin.employees.show');
    

    // county govt officials
    route::get('/county-govt-officials', 'Admin\CountyGovtOfficialsController@index')
        ->name('admin.county-govt-officials.show');
    Route::post('/county-govt-officials', 'Admin\CountyGovtOfficialsController@store')
        ->name('admin.county-govt-officials.add');
    Route::get('/county-govt-officials/edit/{id}', 'Admin\CountyGovtOfficialsController@edit')
        ->name('admin.county-govt-officials.edit');
    Route::post('/county-govt-officials/edit', 'Admin\CountyGovtOfficialsController@update')
        ->name('admin.county-govt-officials.update');
    Route::get('/county-govt-officials/delete/{id}', 'Admin\CountyGovtOfficialsController@delete')
        ->name('admin.county-govt-officials.delete');

    // farmers
    Route::get('/farmers', 'Admin\FarmersController@index')
        ->name('admin.farmers.show');
    Route::post('/farmers', 'Admin\FarmersController@store')
        ->name('admin.farmers.add');
    Route::get('/farmers/detail/{id}', 'Admin\FarmersController@detail')
        ->name('admin.farmers.detail');

    // products
    Route::get('/products/dash', 'Admin\ProductsController@dash')
        ->name('admin.products.dash');

    Route::get('/products/list', 'Admin\ProductsController@list_products')
        ->name('admin.products.show');
    Route::post('/products', 'Admin\ProductsController@store_product')
        ->name('admin.products.store_product');

    Route::get('/products/units', 'Admin\ProductsController@list_units')
        ->name('admin.products.units');
    Route::post('/products/units', 'Admin\ProductsController@store_unit')
        ->name('admin.products.store_unit');
    Route::get('/products/units/{id}', 'Admin\ProductsController@view_edit_unit')
        ->name('admin.products.view_edit_unit');
    Route::post('/products/units/{id}', 'Admin\ProductsController@edit_unit')
        ->name('admin.products.edit_unit');
    Route::get('/products/delete-unit/{id}', 'Admin\ProductsController@delete_unit')
        ->name('admin.products.delete_unit');


    Route::get('/products/categories', 'Admin\ProductsController@list_categories')
        ->name('admin.products.categories');
    Route::post('/products/categories', 'Admin\ProductsController@store_category')
        ->name('admin.products.store_category');
    Route::get('/products/categories/{id}', 'Admin\ProductsController@view_edit_category')
        ->name('admin.products.view_edit_category');
    Route::post('/products/categories/{id}', 'Admin\ProductsController@edit_category')
        ->name('admin.products.edit_category');
    Route::get('/products/delete-category/{id}', 'Admin\ProductsController@delete_category')
        ->name('admin.products.delete_category');

    Route::get('/products/grading', 'Admin\ProductsController@list_grades')
        ->name('admin.products.grades');
    Route::post('/products/grading', 'Admin\ProductsController@store_grade')
        ->name('admin.products.store_grade');
    Route::get('/products/grading/{id}', 'Admin\ProductsController@view_edit_grade')
        ->name('admin.products.view_edit_grade');
    Route::post('/products/grading/{id}', 'Admin\ProductsController@edit_grade')
        ->name('admin.products.edit_grade');
    Route::get('/products/delete-grade/{id}', 'Admin\ProductsController@delete_grade')
        ->name('admin.products.delete_grade');


    Route::get('/products/{id}', 'Admin\ProductsController@view_edit_product')
        ->name('admin.products.view_edit_product');
    Route::post('/products/{id}', 'Admin\ProductsController@edit_product')
        ->name('admin.products.edit_product');

    // collections
    Route::get('/collections', 'Admin\CollectionsController@index')
        ->name('admin.collections.show');



    // roles
    route::get('/roles', 'Admin\RolesController@index')
        ->name('admin.roles.show');
    route::get('/roles/detail-permissions/{id}', 'Admin\RolesController@detail_permissions')
        ->name('admin.roles.show_permissions_tab');
    route::get('/roles/detail-users/{id}', 'Admin\RolesController@detail_users')
        ->name('admin.roles.show_users_tab');

    // support
    route::get('/support', 'Admin\SupportController@index')
        ->name('admin.support.show');
    Route::post("/support/add_comment", "Admin\SupportController@add_comment")
        ->name("admin.support.add-ticket-comment");
    Route::get("/support/{ticket_number}", "Admin\SupportController@view_ticket")
        ->name("admin.support.view-ticket");
    Route::get("/support/resolve-ticket/{ticket_number}", "Admin\SupportController@resolve_ticket")
        ->name("admin.support.resolve-ticket");

});


Route::middleware('role:county govt official')->prefix('county-govt')->group(function () {
    Route::get('/dashboard', 'GovtOfficial\DashboardController@index')->name('govt-official.dashboard');
    
    route::get('/cooperatives', 'GovtOfficial\CooperativesController@index')
        ->name('govt-official.cooperatives.show');
    route::get('/cooperatives/{id}', 'GovtOfficial\CooperativesController@details')
        ->name('govt-official.cooperatives.details');

    route::get('/farmers', 'GovtOfficial\FarmersController@index')
        ->name('govt-official.farmers.show');
    route::get('/farmers/{id}', 'GovtOfficial\FarmersController@details')
        ->name('govt-official.farmers.details');

    
    route::get('/millers', 'GovtOfficial\MillersController@index')
        ->name('govt-official.millers.show');

    
    route::get('/collections', 'GovtOfficial\CollectionsController@index')
        ->name('govt-official.collections.show');

    
    route::get('/sales', 'GovtOfficial\SalesController@index')
        ->name('govt-official.sales.show');
});

Route::middleware('role:cooperative admin')->prefix('cooperative-admin')->group(function () {
    // dashboard
    Route::get("/dashboard", "CooperativeAdmin\DashboardController@index")
        ->name("cooperative-admin.dashboard");
    Route::post("/dashboard/export", "CooperativeAdmin\DashboardController@export_dashboard")
        ->name("cooperative-admin.dashboard.export");

    // branches
    Route::get('/branches-mini-dashboard', 'CooperativeAdmin\BranchesController@branches_mini_dashboard')
        ->name('cooperative-admin.branches.mini-dashboard');
    Route::get("/branches/detail/{id}", "CooperativeAdmin\BranchesController@detail")
        ->name("cooperative-admin.branches.detail");
    Route::post("/branches/set_manager/{id}", "CooperativeAdmin\BranchesController@set_manager")
        ->name("cooperative-admin.branches.set_manager");

    // products
    Route::get('/products', 'CooperativeAdmin\ProductsController@index')
        ->name('cooperative-admin.products.show');
    Route::get('/products/{id}', 'CooperativeAdmin\ProductsController@detail')
        ->name('cooperative-admin.products.detail');

    
    Route::post('/products/product-pricing', 'CooperativeAdmin\ProductsController@store_product_pricing')
        ->name('cooperative-admin.products.store_product_pricing');

    // farmers
    Route::get('/farmers-mini-dashboard', 'CooperativeAdmin\FarmersController@farmer_mini_dashboard')
        ->name('cooperative-admin.farmers.mini-dashboard');
    Route::get('/farmers/show', 'CooperativeAdmin\FarmersController@index')
        ->name('cooperative-admin.farmers.show');
    Route::get('/farmers/view-add-new', 'CooperativeAdmin\FarmersController@view_add_new')
        ->name('cooperative-admin.farmers.view_add_new');
    Route::get('/farmers/view-add-existing', 'CooperativeAdmin\FarmersController@view_add_existing')
        ->name('cooperative-admin.farmers.view_add_existing');
    Route::post('/farmers/add-existing', 'CooperativeAdmin\FarmersController@add_existing')
        ->name('cooperative-admin.farmers.add_existing');
    Route::get('/farmers/detail/{id}', 'CooperativeAdmin\FarmersController@detail')
        ->name('cooperative-admin.farmers.detail');
    Route::post('/farmers', 'CooperativeAdmin\FarmersController@store')
        ->name('cooperative-admin.farmers.add');
    Route::get('/download/farmers-upload-template', function () {
        return Storage::disk('public')->download('templates/coop_farmers_bulk_import.csv');
    })->name('cooperative-admin.download-upload-farmers-template');
    Route::post('/farmers/import-bulk', 'CooperativeAdmin\FarmersController@import_bulk')
        ->name('cooperative-admin.farmers.import-bulk');
    

    // lots
    Route::get('/lots', 'CooperativeAdmin\LotsController@index')
        ->name('cooperative-admin.lots.show');
    Route::get('/lots/{lot_number}', 'CooperativeAdmin\LotsController@detail')
        ->name('cooperative-admin.lots.detail');
    Route::post('/lots/{lot_number}/store-grade-distribution', 'CooperativeAdmin\LotsController@store_grade_distribution')
        ->name('cooperative-admin.lots.store-grade-distribution');

    // collections
    Route::get('/collections-mini-dashboard', 'CooperativeAdmin\CollectionsController@collections_mini_dashboard')
        ->name('cooperative-admin.collections.mini-dashboard');
    Route::get('/collections/show', 'CooperativeAdmin\CollectionsController@index')
        ->name('cooperative-admin.collections.show');
    Route::post('/collections/add', 'CooperativeAdmin\CollectionsController@store')
        ->name('cooperative-admin.collections.store');
    Route::get('/collections/download/{type}', 'CooperativeAdmin\CollectionsController@export_collection')
        ->name("cooperative-admin.collections.export");
    Route::get('/download/collections-upload-template', function () {
        return Storage::disk('public')->download('templates/coop_collections_bulk_import.csv');
    })->name('cooperative-admin.download-upload-collections-template');
    Route::post('/collections/import-bulk', 'CooperativeAdmin\CollectionsController@import_bulk')
        ->name('cooperative-admin.collections.import-bulk');

    // orders
    Route::get("/orders", "CooperativeAdmin\OrdersController@index")
        ->name("cooperative-admin.orders.show");
    Route::get("/orders/{id}", "CooperativeAdmin\OrdersController@detail")
        ->name("cooperative-admin.orders.detail");
    // order-delivery
    Route::get("/order/{order_id}/add-delivery-item", "CooperativeAdmin\OrdersController@add_delivery_item")
        ->name("cooperative-admin.order-delivery.add-item");
    Route::delete("/order/delete-delivery-item/{id}", "CooperativeAdmin\OrdersController@delete_delivery_item")
        ->name("cooperative-admin.order-delivery.delete-item");
    Route::get("/order/publish-delivery-draft/{id}", "CooperativeAdmin\OrdersController@publish_delivery_draft")
        ->name("cooperative-admin.order-delivery.publish-delivery-draft");
    Route::delete("/order/discard-delivery-draft/{id}", "CooperativeAdmin\OrdersController@discard_delivery_draft")
        ->name("cooperative-admin.order-delivery.discard-delivery-draft");

    // delivery


    // settings
    Route::get("/settings", "CooperativeAdmin\SettingsController@index")
        ->name("cooperative-admin.settings.show");
    Route::post("/settings/set-main-product", "CooperativeAdmin\SettingsController@set_main_product")
        ->name("cooperative-admin.settings.set_main_product");

    // support
    Route::get("/support", "CooperativeAdmin\SupportController@index")
        ->name("cooperative-admin.support.show");
    Route::get("/support/add-ticket", "CooperativeAdmin\SupportController@view_add_ticket")
        ->name("cooperative-admin.support.view_add_ticket");
    Route::post("/support/add-ticket", "CooperativeAdmin\SupportController@add_ticket")
        ->name("cooperative-admin.support.add_ticket");
    Route::post("/support/publish-ticket", "CooperativeAdmin\SupportController@publish_ticket")
        ->name("cooperative-admin.support.publish_ticket");
    Route::delete("support/delete-ticket/{id}", "CooperativeAdmin\SupportController@delete_ticket")
        ->name("cooperative-admin.support.delete_ticket");
    Route::post("/support/add_comment", "CooperativeAdmin\SupportController@add_comment")
        ->name("cooperative-admin.support.add-ticket-comment");
    Route::get("/support/confirm_ticket_resolved/{ticket_number}", "CooperativeAdmin\SupportController@confirm_ticket_resolved")
        ->name("cooperative-admin.support.confirm-ticket-resolved");
    Route::get("/support/{ticket_number}", "CooperativeAdmin\SupportController@view_ticket")
        ->name("cooperative-admin.support.view-ticket");

    // transactions
    Route::get("/transactions", "CooperativeAdmin\TransactionController@index")
        ->name("cooperative-admin.transactions.show");
    Route::get("/transactions/add", "CooperativeAdmin\TransactionController@view_add")
        ->name("cooperative-admin.transactions.view-add");
    Route::get("/transactions/add/collection-selector/{id}", "CooperativeAdmin\TransactionController@view_add_collection_selector")
        ->name("cooperative-admin.transactions.view-add-collection-selector");
    Route::post("/transactions/add", "CooperativeAdmin\TransactionController@add")
        ->name("cooperative-admin.transactions.add");
    
});

Route::middleware('role:miller admin')->prefix('miller-admin')->group(function () {
    // warehouses
    Route::get("/warehouses", "MillerAdmin\WarehousesController@index")
        ->name("miller-admin.warehouses.show");
    Route::post('/warehouses/add', 'MillerAdmin\WarehousesController@store')
        ->name('miller-admin.warehouses.store');

    // market/auction
    Route::get("/market-auction", "MillerAdmin\MarketAuctionController@index")
        ->name("miller-admin.market-auction.show");
    Route::get("/market-auction/{coop_id}", "MillerAdmin\MarketAuctionController@view_coop_collections")
        ->name("miller-admin.market-auction.coop-collections.show");
    Route::get("/market-auction/{coop_id}/add_to_cart/{lot_id}", "MillerAdmin\MarketAuctionController@add_lot_to_cart")
        ->name("miller-admin.market-auction.add-to-cart");
    Route::delete("/market-auction/{coop_id}/remove_from_cart/{item_id}", "MillerAdmin\MarketAuctionController@remove_lot_from_cart")
        ->name("miller-admin.market-auction.remove-from-cart");
    Route::delete("/market-auction/{coop_id}/clear_cart", "MillerAdmin\MarketAuctionController@clear_cart")
        ->name("miller-admin.market-auction.clear-cart");
    Route::get("/market-auction/{coop_id}/view-checkout-cart", "MillerAdmin\MarketAuctionController@view_checkout_cart")
        ->name("miller-admin.market-auction.view-checkout-cart");
    Route::post("/market-auction/{coop_id}/checkout-cart", "MillerAdmin\MarketAuctionController@checkout_cart")
        ->name("miller-admin.market-auction.checkout-cart");

    
    // Route::get("/market-auction/{coop_id}/increase_quantity_in_cart/{lot_number}", "MillerAdmin\MarketAuctionController@increase_quantity_in_cart")
    //     ->name("miller-admin.market-auction.increase-quantity-in-cart");
    // Route::put("/market-auction/{coop_id}/set_quantity_in_cart/{lot_number}", "MillerAdmin\MarketAuctionController@set_quantity_in_cart")
    //     ->name("miller-admin.market-auction.set-quantity-in-cart");
    // Route::get("/market-auction/{coop_id}/decrease_quantity_in_cart/{lot_number}", "MillerAdmin\MarketAuctionController@decrease_quantity_in_cart")
    //     ->name("miller-admin.market-auction.decrease-quantity-in-cart");

        
    // orders
    Route::get("/orders", "MillerAdmin\OrdersController@index")
        ->name("miller-admin.orders.show");
    
    // order object
    Route::get("/orders/create-order/{coop_id}", "MillerAdmin\OrdersController@view_create_order")
        ->name("miller-admin.market-auction.coop-collections.view-create-order");
    Route::get("/orders/render-order-row/{item_id}", "MillerAdmin\OrdersController@render_order_row")
        ->name("miller-admin.market-auction.coop-collections.render-order-row");
    Route::get("/orders/empty-order-row", "MillerAdmin\OrdersController@empty_order_row")
        ->name("miller-admin.market-auction.coop-collections.get-empty-order-row");
    Route::get("/orders/create-order-row/{coop_id}", "MillerAdmin\OrdersController@create_order_row")
        ->name("miller-admin.market-auction.coop-collections.create-order-row");


    Route::get("/orders/{id}", "MillerAdmin\OrdersController@detail")
        ->name("miller-admin.orders.detail");
    Route::get("/orders/approve-delivery/{delivery_id}", "MillerAdmin\OrdersController@approve_delivery")
        ->name("miller-admin.orders.approve-delivery");

    // inventory
    Route::get("/inventory/pre-milled", "MillerAdmin\InventoryController@pre_milled")
        ->name("miller-admin.pre-milled-inventory.show");
    Route::post("/inventory/save-milling", "MillerAdmin\InventoryController@save_milling")
        ->name("miller-admin.milling.save");
    Route::get("/inventory/milled", "MillerAdmin\InventoryController@milled")
        ->name("miller-admin.milled-inventory.show");
    Route::get("/inventory/milled/{id}", "MillerAdmin\InventoryController@milled_details")
        ->name("miller-admin.milled-inventory.detail");
    Route::post("/inventory/milled/store-grade", "MillerAdmin\InventoryController@store_milled_inventory_grade")
        ->name("miller-admin.milled-inventory.store-grade");
    Route::delete("/inventory/milled/delete-grade/{id}", "MillerAdmin\InventoryController@delete_milled_inventory_grade")
        ->name("miller-admin.milled-inventory.delete-grade");
    Route::get("/inventory/final-products", "MillerAdmin\InventoryController@final_products")
        ->name("miller-admin.final-products.show");
    Route::post("/inventory/final-products/save-details", "MillerAdmin\InventoryController@save_final_product_details")
        ->name("miller-admin.final-product.save-details");
    Route::post("/inventory/final-products/save-raw-material", "MillerAdmin\InventoryController@save_final_product_raw_material")
        ->name("miller-admin.final-product.save-raw-material");
    Route::delete("/inventory/final-products/delete-raw-material/{id}", "MillerAdmin\InventoryController@delete_final_product_raw_material")
        ->name("miller-admin.final-product.delete-raw-material");
    Route::delete("/inventory/final-product/discard-draft", "MillerAdmin\InventoryController@discard_final_product_draft")
        ->name("miller-admin.final-product.discard-draft");
    Route::get("/inventory/final-products/publish", "MillerAdmin\InventoryController@publish_final_product")
        ->name("miller-admin.final-products.publish");
    // Route::post("/inventory", "MillerAdmin\InventoryController@save")
    //     ->name("miller-admin.inventory.save");
    // Route::post("/inventory", "MillerAdmin\InventoryController@store")
    //     ->name("miller-admin.inventory.store");
    // Route::post("/inventory/add-item", "MillerAdmin\InventoryController@add_item")
    //     ->name("miller-admin.inventory.add-item");
    // Route::get("/inventory/publish/{inventory_number}", "MillerAdmin\InventoryController@publish")
    //     ->name("miller-admin.inventory.publish");

    // inventory auction: supporting ajax
    Route::get("/inventory-auction/final-product/{id}", "MillerAdmin\InventoryAuctionController@retrieve_final_product")
        ->name("miller-admin.inventory-auction.retrieve-final-product");
    Route::get("/inventory-auction/milled-inventory/{id}", "MillerAdmin\InventoryAuctionController@retrieve_milled_inventory")
        ->name("miller-admin.inventory-auction.retrieve-milled-inventory");

    // inventory auction: customers
    Route::get("/inventory-auction/customers", "MillerAdmin\InventoryAuctionController@list_customers")
        ->name("miller-admin.inventory-auction.list-customers");
    Route::get("/inventory-auction/customers/add-customer/",  "MillerAdmin\InventoryAuctionController@add_customer")
        ->name("miller-admin.inventory-auction.add-customer");
    Route::get("/inventory-auction/customers/save-details/{id}", "MillerAdmin\InventoryAuctionController@view_update_customer_details")
        ->name("miller-admin.inventory-auction.view-update-customer-details");
    Route::put("/inventory-auction/customers/update", "MillerAdmin\InventoryAuctionController@update_customer_details")
        ->name("miller-admin.inventory-auction.update-customer-details");
    Route::get("/inventory-auction/customers/details/{id}", "MillerAdmin\InventoryAuctionController@view_customer")
        ->name("miller-admin.inventory-auction.view-customer");
    // inventory auction: quotations
    Route::get("/inventory-auction/quotations", "MillerAdmin\InventoryAuctionController@list_quotations")
        ->name("miller-admin.inventory-auction.list-quotations");
    Route::post("/inventory-auction/quotations/save-basic-details", "MillerAdmin\InventoryAuctionController@save_quotation_basic_details")
        ->name("miller-admin.inventory-auction.quotations.save-basic_details");
    Route::post("/inventory-auction/quotations/save-quotation-item", "MillerAdmin\InventoryAuctionController@save_quotation_item")
        ->name("miller-admin.inventory-auction.quotations.save-quotation-item");
    Route::delete("/inventory-auction/quotations/delete-quotation-item/{id}", "MillerAdmin\InventoryAuctionController@delete_quotation_item")
        ->name("miller-admin.inventory-auction.quotations.delete-quotation-item");
    Route::get("/inventory-auction/quotations/publish-quotation", "MillerAdmin\InventoryAuctionController@publish_quotation")
        ->name("miller-admin.inventory-auction.quotations.publish-quotation");
    Route::get("/inventory-auction/quotations/create-invoice/{id}", "MillerAdmin\InventoryAuctionController@create_invoice_from_quotation")
        ->name("miller-admin.inventory-auction.quotations.create-invoice");
    Route::get("/inventory-auction/quotations/export/{id}", "MillerAdmin\InventoryAuctionController@export_quotation")
        ->name("miller-admin.inventory-auction.quotations.export-quotation");

    // inventory auction: invoices
    Route::get("/inventory-auction/invoices", "MillerAdmin\InventoryAuctionController@list_invoices")
        ->name("miller-admin.inventory-auction.list-invoices");
    Route::post("/inventory-auction/invoices/save-basic-details", "MillerAdmin\InventoryAuctionController@save_invoice_basic_details")
        ->name("miller-admin.inventory-auction.invoices.save-basic-details");
    Route::post("/inventory-auction/invoices/save-invoice-item", "MillerAdmin\InventoryAuctionController@save_invoice_item")
        ->name("miller-admin.inventory-auction.invoices.save-invoice-item");
    Route::delete("/inventory-auction/invoices/delete-invoice-item/{id}", "MillerAdmin\InventoryAuctionController@delete_invoice_item")
        ->name("miller-admin.inventory-auction.invoices.delete-invoice-item");
    Route::get("/inventory-auction/invoices/publish-invoice", "MillerAdmin\InventoryAuctionController@publish_invoice")
        ->name("miller-admin.inventory-auction.invoices.publish-invoice");
    Route::get("/inventory-auction/invoices/create-receipt/{id}", "MillerAdmin\InventoryAuctionController@create_receipt_from_invoice")
        ->name("miller-admin.inventory-auction.invoices.create-receipt");
    Route::get("/inventory-auction/invoices/export/{id}", "MillerAdmin\InventoryAuctionController@export_invoice")
        ->name("miller-admin.inventory-auction.invoices.export-invoice");

    // inventory auction: receipts
    Route::get("/inventory-auction/receipts", "MillerAdmin\InventoryAuctionController@list_receipts")
        ->name("miller-admin.inventory-auction.list-receipts");
    Route::get("/inventory-auction/receipts/export/{id}", "MillerAdmin\InventoryAuctionController@export_receipt")
        ->name("miller-admin.inventory-auction.receipts.export-receipt");

    // inventory auction: sales
    Route::get("/inventory-auction/sales", "MillerAdmin\InventoryAuctionController@list_sales")
        ->name("miller-admin.inventory-auction.list-sales");
    Route::get("/inventory-auction/sales/add-sale/",  "MillerAdmin\InventoryAuctionController@add_sale")
        ->name("miller-admin.inventory-auction.add-sale");
    Route::get("/inventory-auction/sales/save-details/{id}", "MillerAdmin\InventoryAuctionController@view_update_sale")
        ->name("miller-admin.inventory-auction.view-update-sale");

    // transactions
    Route::get("/transactions", "MillerAdmin\TransactionController@index")
        ->name("miller-admin.transactions.show");
    Route::get("/transactions/add", "MillerAdmin\TransactionController@view_add")
        ->name("miller-admin.transactions.view-add");
    Route::get("/transactions/add/miller-selector/{id}", "MillerAdmin\TransactionController@view_add_lot_selector")
        ->name("miller-admin.transactions.view-add-lot-selector");
    Route::post("/transactions/add", "MillerAdmin\TransactionController@add")
        ->name("miller-admin.transactions.add");

    
    // support
    Route::get("/support", "MillerAdmin\SupportController@index")
        ->name("miller-admin.support.show");
    Route::get("/support/add-ticket", "MillerAdmin\SupportController@view_add_ticket")
        ->name("miller-admin.support.view_add_ticket");
    Route::post("/support/add-ticket", "MillerAdmin\SupportController@add_ticket")
        ->name("miller-admin.support.add_ticket");
    Route::post("/support/publish-ticket", "MillerAdmin\SupportController@publish_ticket")
        ->name("miller-admin.support.publish_ticket");
    Route::delete("support/delete-ticket/{id}", "MillerAdmin\SupportController@delete_ticket")
        ->name("miller-admin.support.delete_ticket");
    Route::post("/support/add_comment", "MillerAdmin\SupportController@add_comment")
        ->name("miller-admin.support.add-ticket-comment");
    Route::get("/support/confirm_ticket_resolved/{ticket_number}", "MillerAdmin\SupportController@confirm_ticket_resolved")
        ->name("miller-admin.support.confirm-ticket-resolved");
    Route::get("/support/{ticket_number}", "MillerAdmin\SupportController@view_ticket")
        ->name("miller-admin.support.view-ticket");

    // tracking tree
    Route::get("/tracking-tree", "MillerAdmin\TrackingTreeController@index")
        ->name("miller-admin.tracking-tree.show");
    Route::get("/tracking-tree/root-identifier/{root_type}", "MillerAdmin\TrackingTreeController@root_identifier")
        ->name("miller-admin.tracking-tree.root_identifier");
    Route::post("/tracking-tree/root-details", "MillerAdmin\TrackingTreeController@root_details")
        ->name("miller-admin.tracking-tree.root_details");
    Route::post("/tracking-tree/node-children", "MillerAdmin\TrackingTreeController@node_children")
        ->name("miller-admin.tracking-tree.node-children");

});

Route::middleware('role:cooperative admin|employee')->prefix('cooperative')->group(function () {



    Route::middleware('financial_period')->group(function () {
        Route::middleware('module_gate:Farmer CRM')->group(function () {
            //farmer
            Route::get('/farmer/route', 'RouteController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['Farmer CRM']['routes'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.routes.show');
            Route::post('/farmer/route', 'RouteController@store')
                ->middleware('module_permission:' . config('enums.system_modules')['Farmer CRM']['routes'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.route.add');
            Route::post('/farmer/route/{id}', 'RouteController@edit')
                ->middleware('module_permission:' . config('enums.system_modules')['Farmer CRM']['routes'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.route.edit');
            Route::get('/farmer/routes/download/{type}', 'RouteController@download_routes')
                ->middleware('module_permission:' . config('enums.system_modules')['Farmer CRM']['routes'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.routes.download');
            Route::get('/farmer/show', 'FarmerController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['Farmer CRM']['farmers'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.farmers.show');
            Route::get('/farmer/{farmerId}/edit', 'FarmerController@edit_farmer')
                ->middleware('module_permission:' . config('enums.system_modules')['Farmer CRM']['farmers'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.farmer.edit');
            Route::post('/farmer/{farmerId}/update', 'FarmerController@update_farmer_profile')
                ->middleware('module_permission:' . config('enums.system_modules')['Farmer CRM']['farmers'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.farmer.profile.update');
            Route::post('/farmer/add', 'FarmerController@store')
                ->middleware('module_permission:' . config('enums.system_modules')['Farmer CRM']['farmers'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.farmer.add');
            Route::post('/farmer/bank_branch/{bank_id}', 'FarmerController@get_bank_branches')
                ->middleware('module_permission:' . config('enums.system_modules')['Farmer CRM']['farmers'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.farmer.bank_branch.get');
            Route::get('/farmer/download-farmers/{type}', 'FarmerController@export_farmers')
                ->middleware('module_permission:' . config('enums.system_modules')['Farmer CRM']['farmers'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.farmer.download');
            Route::post('/farmer/import', 'FarmerController@importFarmers')
                ->middleware('module_permission:' . config('enums.system_modules')['Farmer CRM']['farmers'] . ',' . config('enums.system_permissions')['create'])
                ->name('farmer.bulk.import');
        });

        Route::middleware('module_gate:Product Management')->group(function () {
            //products
            Route::get('/products/units', 'UnitController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['Product Management']['units'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.units.show');
            Route::post('/products/unit', 'UnitController@store')
                ->middleware('module_permission:' . config('enums.system_modules')['Product Management']['units'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.unit.add');
            Route::post('/products/unit/{id}', 'UnitController@edit')
                ->middleware('module_permission:' . config('enums.system_modules')['Product Management']['units'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.unit.edit');
            Route::get('/products/unit/download/{type}', 'UnitController@export_units')
                ->middleware('module_permission:' . config('enums.system_modules')['Product Management']['units'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.unit.download');
            Route::get('/products/categories', 'CategoryController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['Product Management']['categories'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.categories.show');
            Route::post('/products/category', 'CategoryController@store')
                ->middleware('module_permission:' . config('enums.system_modules')['Product Management']['categories'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.category.add');
            Route::post('/products/category/{id}', 'CategoryController@edit')
                ->middleware('module_permission:' . config('enums.system_modules')['Product Management']['categories'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.category.edit');
            Route::get('/products/show', 'ProductController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['Product Management']['products'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.products.show');
            Route::post('/products/add', 'ProductController@store')
                ->middleware('module_permission:' . config('enums.system_modules')['Product Management']['products'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.product.add');
            Route::post('/product/{id}/edit', 'ProductController@edit')
                ->middleware('module_permission:' . config('enums.system_modules')['Product Management']['products'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.product.edit');
            Route::get('/products/download/{type}', 'ProductController@download_products')
                ->middleware('module_permission:' . config('enums.system_modules')['Product Management']['products'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.products.download');
            Route::get('/products/suppliers/show', 'ProductController@get_suppliers')
                ->middleware('module_permission:' . config('enums.system_modules')['Product Management']['suppliers'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.products.suppliers.show');
            Route::get('/products/suppliers/download/{type}', 'ProductController@download_products_suppliers')
                ->middleware('module_permission:' . config('enums.system_modules')['Product Management']['suppliers'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.products.suppliers.download');
            Route::get('/products/suppliers/{farmer_id}', 'ProductController@get_farmer_products')
                ->middleware('module_permission:' . config('enums.system_modules')['Product Management']['suppliers'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.farmer.products.suppliers.show');
            Route::post('add/products/suppliers/{farmer_id}', 'ProductController@add_products_to_farmer')
                ->middleware('module_permission:' . config('enums.system_modules')['Product Management']['suppliers'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.farmer.add.products.suppliers');
            Route::get('add/products/suppliers/{farmer_id}/download/{type}', 'ProductController@download_farmer_products')
                ->middleware('module_permission:' . config('enums.system_modules')['Product Management']['suppliers'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.farmer.add.products.suppliers.download');
        });

        // logistics
        Route::middleware('module_gate:Logistics')->prefix('logistics')->group(function () {

            // dashboard
            Route::get('/dashboard', 'TripController@dashboard')
                ->middleware('module_permission:' . config('enums.system_modules')['Logistics']['trip_management'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.logistics.dashboard');

            // vehicle types
            Route::group(['prefix' => '/vehicle_types'], function () {
                Route::get('/', 'VehicleTypeController@index')
                    ->middleware('module_permission:' . config('enums.system_modules')['Logistics']['vehicle_types'] . ',' . config('enums.system_permissions')['view'])
                    ->name('cooperative.logistics.vehicle_types');
                Route::post('/', 'VehicleTypeController@store')
                    ->middleware('module_permission:' . config('enums.system_modules')['Logistics']['vehicle_types'] . ',' . config('enums.system_permissions')['create'])
                    ->name('cooperative.logistics.vehicle_types.add');
                Route::post('/{id}', 'VehicleTypeController@update')
                    ->middleware('module_permission:' . config('enums.system_modules')['Logistics']['vehicle_types'] . ',' . config('enums.system_permissions')['edit'])
                    ->name('cooperative.logistics.vehicle_types.update');
            });

            // vehicles
            Route::group(['prefix' => '/vehicles'], function () {
                Route::get('/', 'VehicleController@index')
                    ->middleware('module_permission:' . config('enums.system_modules')['Logistics']['vehicles'] . ',' . config('enums.system_permissions')['view'])
                    ->name('cooperative.logistics.vehicles');
                Route::post('/', 'VehicleController@store')
                    ->middleware('module_permission:' . config('enums.system_modules')['Logistics']['vehicles'] . ',' . config('enums.system_permissions')['create'])
                    ->name('cooperative.logistics.vehicles.add');
                Route::get('/{id}', 'VehicleController@show')
                    ->middleware('module_permission:' . config('enums.system_modules')['Logistics']['vehicles'] . ',' . config('enums.system_permissions')['view'])
                    ->name('cooperative.logistics.vehicles.show'); //** */
                Route::post('/{id}', 'VehicleController@update')
                    ->middleware('module_permission:' . config('enums.system_modules')['Logistics']['vehicles'] . ',' . config('enums.system_permissions')['edit'])
                    ->name('cooperative.logistics.vehicles.update'); //** */
            });

            // transporters
            Route::group(['prefix' => '/transporters'], function () {
                Route::get('/', 'TransportProviderController@index')
                    ->middleware('module_permission:' . config('enums.system_modules')['Logistics']['transport_providers'] . ',' . config('enums.system_permissions')['view'])
                    ->name('cooperative.logistics.transporters');
                Route::post('/', 'TransportProviderController@store')
                    ->middleware('module_permission:' . config('enums.system_modules')['Logistics']['transport_providers'] . ',' . config('enums.system_permissions')['create'])
                    ->name('cooperative.logistics.transporters.add');
                Route::get('/{id}', 'TransportProviderController@show')
                    ->middleware('module_permission:' . config('enums.system_modules')['Logistics']['transport_providers'] . ',' . config('enums.system_permissions')['view'])
                    ->name('cooperative.logistics.transporters.show');
                Route::get('/{id}/vehicles', 'TransportProviderController@getVehiclesByTransporterId')
                    ->middleware('module_permission:' . config('enums.system_modules')['Logistics']['transport_providers'] . ',' . config('enums.system_permissions')['create'])
                    ->name('cooperative.logisticts.transporters.vehicles');
                Route::post('/{id}', 'TransportProviderController@update')
                    ->middleware('module_permission:' . config('enums.system_modules')['Logistics']['transport_providers'] . ',' . config('enums.system_permissions')['edit'])
                    ->name('cooperative.logistics.transporters.update');
                Route::post('/{id}/vehicles', 'TransportProviderController@storeVehicle')
                    ->middleware('module_permission:' . config('enums.system_modules')['Logistics']['transport_providers'] . ',' . config('enums.system_permissions')['create'])
                    ->name('cooperative.logistics.transporters.add-vehicle');
                Route::post('/{id}/vehicles/{vid}', 'TransportProviderController@updateVehicle')
                    ->middleware('module_permission:' . config('enums.system_modules')['Logistics']['transport_providers'] . ',' . config('enums.system_permissions')['edit'])
                    ->name('cooperative.logistics.transporters.update-vehicle');
            });

            // weighbridges
            Route::group(['prefix' => '/weighbridges'], function () {
                Route::get('/', 'WeighBridgeController@index')
                    ->middleware('module_permission:' . config('enums.system_modules')['Logistics']['weighbridge'] . ',' . config('enums.system_permissions')['view'])
                    ->name('cooperative.logistics.weighbridges');
                Route::post('/', 'WeighBridgeController@store')
                    ->middleware('module_permission:' . config('enums.system_modules')['Logistics']['weighbridge'] . ',' . config('enums.system_permissions')['create'])
                    ->name('cooperative.logistics.weighbridges.add');
                Route::get('/{id}', 'WeighBridgeController@show')
                    ->middleware('module_permission:' . config('enums.system_modules')['Logistics']['weighbridge'] . ',' . config('enums.system_permissions')['view'])
                    ->name('cooperative.logistics.weighbridges.show'); //** */
                Route::post('/{id}', 'WeighBridgeController@update')
                    ->middleware('module_permission:' . config('enums.system_modules')['Logistics']['weighbridge'] . ',' . config('enums.system_permissions')['edit'])
                    ->name('cooperative.logistics.weighbridges.update'); //** */
            });


            // trips
            Route::group(['prefix' => '/trips'], function () {
                Route::get('/', 'TripController@index')
                    ->middleware('module_permission:' . config('enums.system_modules')['Logistics']['trip_management'] . ',' . config('enums.system_permissions')['view'])
                    ->name('cooperative.logistics.trips');
                Route::post('/', 'TripController@store')
                    ->middleware('module_permission:' . config('enums.system_modules')['Logistics']['trip_management'] . ',' . config('enums.system_permissions')['create'])
                    ->name('cooperative.logistics.trips.add');
                Route::get('/{id}', 'TripController@show')
                    ->middleware('module_permission:' . config('enums.system_modules')['Logistics']['trip_management'] . ',' . config('enums.system_permissions')['view'])
                    ->name('cooperative.logistics.trips.show');
                Route::post('/{id}', 'TripController@update')
                    ->middleware('module_permission:' . config('enums.system_modules')['Logistics']['trip_management'] . ',' . config('enums.system_permissions')['edit'])
                    ->name('cooperative.logistics.trips.update');
                Route::post('/{id}/weight', 'TripController@recordWeight')
                    ->middleware('module_permission:' . config('enums.system_modules')['Logistics']['trip_management'] . ',' . config('enums.system_permissions')['create'])
                    ->name('cooperative.logistics.trips.record-weight');
            });

            // locations
            //            Route::group(['prefix' => '/locations'], function () {
            //                Route::get('/search', 'TripController@locationSearch')->name('cooperative.logistics.location-search');
            //            });
        });

        //collections
        Route::middleware('module_gate:Collections')->group(function () {
            Route::get('/collections', 'CollectionController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['Collections']['collect'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.collections.show');
            Route::get('/collections/product/{id}', 'CollectionController@collectionsByProduct')
                ->middleware('module_permission:' . config('enums.system_modules')['Collections']['collect'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.collections.product.show');
            Route::post('/collection/add', 'CollectionController@store')
                ->middleware('module_permission:' . config('enums.system_modules')['Collections']['collect'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.collection.add');
            Route::post('/collection/{id}/edit', 'CollectionController@update')
                ->middleware('module_permission:' . config('enums.system_modules')['Collections']['collect'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.collection.update');
            Route::get('/collections/dashboard', 'CollectionController@getReports')
                ->middleware('module_permission:' . config('enums.system_modules')['Collections']['dashboard'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.collections.reports');
            Route::post('/collections/reports/stats', 'CollectionController@get_dashboard_stats')
                ->middleware('module_permission:' . config('enums.system_modules')['Collections']['dashboard'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.collections.reports.stats');
            Route::get('/collections/download/{type}', 'CollectionController@export_collection')
                ->middleware('module_permission:' . config('enums.system_modules')['Collections']['collect'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.collections.download');
            Route::get('/collections/farmer/{farmer_id}/view', 'CollectionController@view_farmer_collection')
                ->middleware('module_permission:' . config('enums.system_modules')['Collections']['collect'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.collections.farmer.view');
            Route::get('/collections/download/{type}/farmer/{farmer_id}', 'CollectionController@export_farmer_collection')
                ->middleware('module_permission:' . config('enums.system_modules')['Collections']['collect'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.collections.farmer.download');
            Route::get('/farmers/all', 'CollectionController@getFarmers')
                ->middleware('module_permission:' . config('enums.system_modules')['Collections']['collect'] . ',' . config('enums.system_permissions')['create']);
            Route::get('/agents/all', 'CollectionController@getAgents')
                ->middleware('module_permission:' . config('enums.system_modules')['Collections']['collect'] . ',' . config('enums.system_permissions')['create']);
            Route::get('/std-qualities', 'CollectionController@getStandardQualities')
                ->middleware('module_permission:' . config('enums.system_modules')['Collections']['collect'] . ',' . config('enums.system_permissions')['create']);
            Route::get('/collections/quality-standards', 'CollectionQualityStandardController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['Collections']['quality_std'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.quality-standards.show');
            Route::post('/collection/quality-standard', 'CollectionQualityStandardController@store')
                ->middleware('module_permission:' . config('enums.system_modules')['Collections']['quality_std'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.quality-standard.add');
            Route::post('/collection/quality-standard/{id}', 'CollectionQualityStandardController@edit')
                ->middleware('module_permission:' . config('enums.system_modules')['Collections']['quality_std'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.quality-standard.edit');
            Route::get('/collection/quality-standard/download/{type}', 'CollectionQualityStandardController@download')
                ->middleware('module_permission:' . config('enums.system_modules')['Collections']['quality_std'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.quality-standard.download');
            Route::get('/collections/submitted', 'CollectionController@submittedCollections')
                ->middleware('module_permission:' . config('enums.system_modules')['Collections']['submitted_collection'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.submitted.collections');
            Route::post('/collections/submitted/{id}', 'CollectionController@updateSubmissionStatus')
                ->middleware('module_permission:' . config('enums.system_modules')['Collections']['submitted_collection'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.submitted.collection.update');
            Route::get('/collections/product/{id}/download/{type}', 'CollectionController@export_collection_by_product')
                ->middleware('module_permission:' . config('enums.system_modules')['Collections']['collect'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.collection.product.download');
            Route::get('/collections/submitted/download/{type}', 'CollectionController@export_submitted_collections')
                ->middleware('module_permission:' . config('enums.system_modules')['Collections']['submitted_collection'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.submitted.collection.download');

            Route::get('/collections/{id}/receipt/download', 'CollectionController@downloadReceipt')
                ->middleware('module_permission:' . config('enums.system_modules')['Collections']['submitted_collection'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.collection.receipt.download');
            Route::get('/collections/bulk-payment', 'CollectionController@bulk_payment')
                ->middleware('module_permission:' . config('enums.system_modules')['Collections']['bulk_payment'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.collection.bulk-payment');

            Route::post('/collections/bulk-payment/pay', 'CollectionController@bulk_payment_pay')
                ->middleware('module_permission:' . config('enums.system_modules')['Collections']['bulk_payment'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.collection.bulk-payment.pay');
            Route::get('/collections/bulk-payment/download/{type}', 'CollectionController@export_bulk_payments')
                ->middleware('module_permission:' . config('enums.system_modules')['Collections']['bulk_payment'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.collection.bulk-payment.download');
            Route::get('/collections/processed-bulk-payment/download/{type}', 'CollectionController@export_processed_bulk_payments')
                ->middleware('module_permission:' . config('enums.system_modules')['Collections']['bulk_payment'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.collection.processed-payment.download');
            Route::post('/collections/bulk-payment/{id}/complete', 'CollectionController@complete_bulk_payments')
                ->middleware('module_permission:' . config('enums.system_modules')['Collections']['bulk_payment'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.collection.complete-payment');

            Route::get('/collections/bulk-payment/{id}/farmers', 'CollectionController@bulk_payment_farmers')
                ->middleware('module_permission:' . config('enums.system_modules')['Collections']['bulk_payment'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.collection.bulk-payment-farmers');

            Route::get('/collections/bulk-payment/{batch}/farmers/download/{type}', 'CollectionController@export_bulk_payment_farmers')
                ->middleware('module_permission:' . config('enums.system_modules')['Collections']['bulk_payment'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.collection.bulk-payment-farmers.download');
        });

        //bank
        Route::middleware('module_gate:Bank Management')->group(function () {
            Route::get('/bank/show', 'BankController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['Bank Management']['banks'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.bank.show');
            Route::post('/banks/add', 'BankController@store')
                ->middleware('module_permission:' . config('enums.system_modules')['Bank Management']['banks'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.bank.add');
            Route::post('/banks/edit/{id}', 'BankController@edit')
                ->middleware('module_permission:' . config('enums.system_modules')['Bank Management']['banks'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.bank.edit');
            Route::get('/bank/bank_branches', 'BankBranchController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['Bank Management']['branches'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.bank_branch.show');
            Route::post('/bank/bank_branch/add', 'BankBranchController@store')
                ->middleware('module_permission:' . config('enums.system_modules')['Bank Management']['banks'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.bank_branch.add');
        });

        //Farm
        Route::middleware('module_gate:Farm Management')->group(function () {
            Route::get('/farm/breeds', 'BreedController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['breed_registration'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.farm.breeds');
            Route::post('/farm/breed', 'BreedController@store')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['breed_registration'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.breed.add');
            Route::post('/farm/breed/{id}/edit', 'BreedController@edit')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['breed_registration'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.breed.edit');
            Route::get('/farm/livestock-poultry', 'CowController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['livestock_poultry'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.farm.animals');
            Route::get('/farm/livestock-poultry/download/{type}', 'CowController@export_farm_livestock_poultry')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['livestock_poultry'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.farm.animals.download');
            Route::post('/farm/livestock-poultry', 'CowController@store')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['livestock_poultry'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.animal.add');
            Route::post('/farm/animal/{id}/edit', 'CowController@edit')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['livestock_poultry'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.animal.edit');
            Route::get('/farm/crop-calendar-stages', 'CropManagementController@calendar_stages')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['calendar_stages'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.farm.crop-calendar-stages');
            Route::post('/farm/crop-calendar-stage', 'CropManagementController@add_calendar_stages')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['calendar_stages'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.farm.add.crop-calendar-stage');
            Route::post('/farm/crop-calendar-stage/{id}', 'CropManagementController@edit_calendar_stages')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['calendar_stages'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.farm.edit.crop-calendar-stage');
            Route::get('/farm/crop-calendar-stage/type/{type}/{id}/stages', 'CropManagementController@calendar_stage_stages')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['calendar_stages'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.farm.crop-calendar-stages.stages');
            Route::post('/farm/crop-calendar-stage/type/{type}/{id}/stages/add', 'CropManagementController@add_calendar_stage_stages')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['calendar_stages'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.farm.crop-calendar-stages.stages.add');
            Route::post('/farm/crop-calendar-stage/stage/{id}/edit', 'CropManagementController@edit_calendar_stage_stages')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['calendar_stages'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.farm.crop-calendar-stages.stages.edit');
            Route::post('/farm/crop-calendar-stage/stage/{id}/delete', 'CropManagementController@delete_calendar_stage_stages')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['calendar_stages'] . ',' . config('enums.system_permissions')['delete'])
                ->name('cooperative.farm.crop-calendar-stages.stages.delete');
            Route::get('/farm/crops', 'CropManagementController@crop')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['crop_details'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.farm.crops');
            Route::get('/crops/details/download/{type}', 'CropManagementController@export_farm_crops_details')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['crop_details'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.crops-details.download');

            Route::post('/farm/crop', 'CropManagementController@addCrop')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['crop_details'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.farm.add.crop');
            Route::post('/farm/crop/{id}', 'CropManagementController@editCrop')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['crop_details'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.farm.edit.crop');
            Route::get('/farm/farmer-calendar', 'CropManagementController@farmer_crops')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['farmer_calendar'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.farm.farmer-crops');
            Route::get('/farm/farmer-calendar/download/{type}', 'CropManagementController@export_farmer_calendar')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['farmer_calendar'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.farm-calendar.download');
            Route::post('/farm/farmer-crop', 'CropManagementController@add_farmer_crop')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['farmer_calendar'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.farm.add.farmer-crop');
            Route::get('/farm/farmer-calendar/{id}/trackers/{type}', 'CropManagementController@farmer_crop_stages')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['farmer_calendar'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.farm.farmer-crop.trackers');
            Route::post('/farm/farmer-crop/{id}/add-tracker', 'CropManagementController@add_farmer_crop_stages')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['farmer_calendar'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.farm.add.tracker.farmer-crop');
            Route::get('/farm/farmer-calendar/tracker/{id}/cost-break-down', 'CropManagementController@get_cost_break_down')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['farmer_calendar'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.farm.tracker.cost-break-down');
            Route::post('/farm/farmer-crop/tracker/cost-break-down/{id}/edit', 'CropManagementController@edit_cost_break_down')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['farmer_calendar'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.farm.tracker.cost-break-down.edit');
            Route::post('/farm/farmer-crop/tracker/cost-break-down/{id}/add', 'CropManagementController@add_new_cost')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['farmer_calendar'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.farm.tracker.cost-break-down.add');
            Route::post('/farm/farmer-crop/tracker/cost-break-down/{id}/delete', 'CropManagementController@delete_cost_break_down')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['farmer_calendar'] . ',' . config('enums.system_permissions')['delete'])
                ->name('cooperative.farm.tracker.cost-break-down.delete');
            Route::post('/farm/farmer-crop/edit-tracker/{tracker_id}', 'CropManagementController@edit_progress_tracker')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['farmer_calendar'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.farm.edit.tracker-progress');
            Route::post('/farm/farmer-crop/{id}/calendar-data', 'CropManagementController@get_farmer_crop_calendar')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['farmer_calendar'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.farm.crop-stages-calendar-data');
            Route::get('/farm/farm-units', 'CropManagementController@farm_unit')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['farm_units'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.farm-units');
            Route::post('/farm/farmer-unit', 'CropManagementController@add_farm_unit')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['farm_units'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.farm-unit.add');
            Route::post('/farm/farmer-unit/{id}/edit', 'CropManagementController@edit_farm_unit')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['farm_units'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.farm-unit.edit');
            Route::get('/farm/farmer-yields', 'CropManagementController@farm_yields')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['yields'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.farmers-yields');
            Route::post('/farm/farmer-yield', 'CropManagementController@add_farmer_yield')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['yields'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.farmers-yield.add');
            Route::post('/farm/farmer-yield/{id}/edit', 'CropManagementController@edit_yield')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['yields'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.farmers-yield.edit');
            Route::post('/farm/farmer-yield/{id}/delete', 'CropManagementController@delete_yields')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['yields'] . ',' . config('enums.system_permissions')['delete'])
                ->name('cooperative.farmers-yield.delete');
            Route::get('/farm/configure-expected-yields', 'CropManagementController@configure_expected_yields')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['yield_config'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.configure-expected-yields');
            Route::post('/farm/configure-expected-yield', 'CropManagementController@add_expected_yield_config')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['yield_config'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.configure-expected-yield');
            Route::post('/farm/configure-expected-yield/{id}', 'CropManagementController@edit_expected_yield_config')
                ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['yield_config'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.configure-expected-yield.edit');
        });

        //disease
        Route::middleware('module_gate:Disease Management')->group(function () {
            Route::get('/disease/categories', 'DiseaseCategoryController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['Disease Management']['categories'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.disease.categories');
            Route::post('/disease-category/add', 'DiseaseCategoryController@store')
                ->middleware('module_permission:' . config('enums.system_modules')['Disease Management']['categories'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.disease-category.add');
            Route::post('/disease-category/{id}/edit', 'DiseaseCategoryController@edit')
                ->middleware('module_permission:' . config('enums.system_modules')['Disease Management']['categories'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.disease-category.edit');
            Route::get('/disease/show', 'DiseaseController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['Disease Management']['diseases'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.disease.show');
            Route::post('/disease/add', 'DiseaseController@store')
                ->middleware('module_permission:' . config('enums.system_modules')['Disease Management']['diseases'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.disease.add');
            Route::post('/disease/{id}/edit', 'DiseaseController@edit')
                ->middleware('module_permission:' . config('enums.system_modules')['Disease Management']['diseases'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.disease.edit');
            Route::get('/disease/cases', 'ReportedCasesController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['Disease Management']['disease_cases'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.disease.reported_cases');
            Route::get('/disease/cases/download/{type}', 'ReportedCasesController@export_reported_cases')
                ->middleware('module_permission:' . config('enums.system_modules')['Disease Management']['disease_cases'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.disease.reported_cases.download');
            Route::post('/disease/case/add', 'ReportedCasesController@add_case')
                ->middleware('module_permission:' . config('enums.system_modules')['Disease Management']['disease_cases'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.disease.case.add');
            Route::post('/disease/case/edit/{id}', 'ReportedCasesController@edit_case')
                ->middleware('module_permission:' . config('enums.system_modules')['Disease Management']['disease_cases'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.disease.case.edit');
            Route::get('/disease/mini-dashboard', 'DiseaseMiniDashboardController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['Disease Management']['dashboard'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.disease.mini-dashboard');
            Route::post('/disease/mini-dashboard/stats', 'DiseaseMiniDashboardController@stats')
                ->middleware('module_permission:' . config('enums.system_modules')['Disease Management']['dashboard'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.disease.mini-dashboard.stats');
            Route::post('/disease/mini-dashboard/disease_map_data', 'DiseaseMiniDashboardController@disease_map_data')
                ->middleware('module_permission:' . config('enums.system_modules')['Disease Management']['dashboard'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.disease.mini-dashboard.disease_map_data');
        });

        //vet
        Route::middleware('module_gate:Vet')->group(function () {
            Route::get('/vet/show', 'VetController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['Vet & Extension Services']['vets'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.vet.show');
            Route::get('/vets/download/{type}', 'VetController@download_vets')
                ->middleware('module_permission:' . config('enums.system_modules')['Vet & Extension Services']['vets'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.vets.download');
            Route::post('/vet/add', 'VetController@store')
                ->middleware('module_permission:' . config('enums.system_modules')['Vet & Extension Services']['vets'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.vet.add');
            Route::get('/vet/services/show', 'VetServiceController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['Vet & Extension Services']['services'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.vet.service.show');
            Route::post('/vet/services/add', 'VetServiceController@store')
                ->middleware('module_permission:' . config('enums.system_modules')['Vet & Extension Services']['services'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.vet.service.add');
            Route::get('/vet/items/show', 'VetItemsController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['Vet & Extension Services']['items'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.vet.items.show');
            Route::get('/vet/items/download/{type}', 'VetItemsController@export_vet_items')
                ->middleware('module_permission:' . config('enums.system_modules')['Vet & Extension Services']['items'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.vet.items.download');
            Route::post('/vet/item/add', 'VetItemsController@store')
                ->middleware('module_permission:' . config('enums.system_modules')['Vet & Extension Services']['items'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.vet.item.add');
            Route::get('/vet/bookings/show', 'VetController@booking_index')
                ->middleware('module_permission:' . config('enums.system_modules')['Vet & Extension Services']['bookings'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.vet.bookings.show');
            Route::get('/vet/bookings/download/{type}', 'VetController@export_vet_bookings')
                ->middleware('module_permission:' . config('enums.system_modules')['Vet & Extension Services']['bookings'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.vet.bookings.download');
            Route::get('/vet/bookings/fetch', 'VetController@get_bookings')
                ->middleware('module_permission:' . config('enums.system_modules')['Vet & Extension Services']['bookings'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.vet.bookings.fetch');
            Route::post('/vet/bookings/add', 'VetController@add_bookings')
                ->middleware('module_permission:' . config('enums.system_modules')['Vet & Extension Services']['bookings'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.vet.bookings.add');
            Route::post('/vet/booking/{id}/edit', 'VetController@edit_bookings')
                ->middleware('module_permission:' . config('enums.system_modules')['Vet & Extension Services']['bookings'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.edit.vet-booking');
            Route::post('/vet/booking/{id}/edit/status', 'VetController@edit_booking_status')
                ->middleware('module_permission:' . config('enums.system_modules')['Vet & Extension Services']['bookings'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.edit.vet-booking.status');
            Route::post('/vet/booking/{id}/add-vet-items', 'VetController@add_vet_items_to_bookings')
                ->middleware('module_permission:' . config('enums.system_modules')['Vet & Extension Services']['bookings'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.add.vet-booking.items');
        });

        //vet & disease
        Route::middleware('module_gate:Vet')->group(function () {
            Route::middleware('module_gate:Disease')->group(function () {
                Route::get('/disease/case/{id}/book-vet', 'ReportedCasesController@book_vet')
                    ->middleware('module_permission:' . config('enums.system_modules')['Vet & Extension Services']['bookings'] . ',' . config('enums.system_permissions')['create'])
                    ->name('cooperative.disease.case.book-vet');
                Route::post('/disease/case/{id}/book', 'ReportedCasesController@book')
                    ->middleware('module_permission:' . config('enums.system_modules')['Vet & Extension Services']['bookings'] . ',' . config('enums.system_permissions')['create'])
                    ->name('cooperative.disease.case.book');
            });
        });

        //hr
        Route::middleware('module_gate:HR Management')->prefix('/hr')->group(function () {
            Route::get('/dashboard', 'EmployeeController@hrReports')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['dashboard'] . ',' . config('enums.system_permissions')['view'])
                ->name('hr.dashboard');
            //branches
            Route::get('/branches', 'CoopBranchController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['branches'] . ',' . config('enums.system_permissions')['view'])
                ->name('hr.branches.show');
            Route::get('/branch/{id}', 'CoopBranchController@edit')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['branches'] . ',' . config('enums.system_permissions')['view'])
                ->name('hr.branches.detail');
            Route::post('/branches/add', 'CoopBranchController@store')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['branches'] . ',' . config('enums.system_permissions')['create'])
                ->name('hr.branches.add');
            Route::post('/branches/edit', 'CoopBranchController@update')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['branches'] . ',' . config('enums.system_permissions')['edit'])
                ->name('hr.branches.edit');
            Route::get('/branches/delete/{id}', 'CoopBranchController@delete')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['branches'] . ',' . config('enums.system_permissions')['delete'])
                ->name('hr.branches.delete');
            //departments
            Route::get('/departments', 'CoopDepartmentController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['departments'] . ',' . config('enums.system_permissions')['view'])
                ->name('hr.departments.show');
            Route::get('/department/{id}', 'CoopDepartmentController@edit')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['departments'] . ',' . config('enums.system_permissions')['view'])
                ->name('hr.departments.detail');
            Route::post('/departments/add', 'CoopDepartmentController@store')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['departments'] . ',' . config('enums.system_permissions')['create'])
                ->name('hr.departments.add');
            Route::post('/departments/edit', 'CoopDepartmentController@update')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['departments'] . ',' . config('enums.system_permissions')['edit'])
                ->name('hr.departments.edit');
            Route::get('/departments/delete/{id}', 'CoopDepartmentController@delete')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['departments'] . ',' . config('enums.system_permissions')['delete'])
                ->name('hr.departments.delete');

            Route::get('/departments/{id}/employees', 'EmployeeController@deptEmployees')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['employees'] . ',' . config('enums.system_permissions')['view'])
                ->name('hr.departments.employees');
            //types
            Route::get('/employment-types', 'EmploymentTypeController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['job_type'] . ',' . config('enums.system_permissions')['view'])
                ->name('hr.employment-types.show');
            Route::post('/employment-types/add', 'EmploymentTypeController@store')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['job_type'] . ',' . config('enums.system_permissions')['create'])
                ->name('hr.employment-types.add');
            Route::post('/employment-types/edit', 'EmploymentTypeController@update')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['job_type'] . ',' . config('enums.system_permissions')['edit'])
                ->name('hr.employment-types.edit');
            Route::get('/employment-types/delete/{id}', 'EmploymentTypeController@delete')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['job_type'] . ',' . config('enums.system_permissions')['delete'])
                ->name('hr.employment-types.delete');
            //positions
            Route::get('/job-positions', 'JobPositionsController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['job_positions'] . ',' . config('enums.system_permissions')['view'])
                ->name('hr.job-positions.show');
            Route::post('/job-positions/add', 'JobPositionsController@store')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['job_positions'] . ',' . config('enums.system_permissions')['create'])
                ->name('hr.job-positions.add');
            Route::post('/job-positions/edit', 'JobPositionsController@update')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['job_positions'] . ',' . config('enums.system_permissions')['edit'])
                ->name('hr.job-positions.edit');
            Route::get('/job-positions/delete/{id}', 'JobPositionsController@delete')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['job_positions'] . ',' . config('enums.system_permissions')['delete'])
                ->name('hr.job-positions.delete');
            //employee
            Route::get('/employees', 'EmployeeController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['employees'] . ',' . config('enums.system_permissions')['view'])
                ->name('hr.employees.show');
            Route::get('/employees/files', 'EmployeeController@files')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['files'] . ',' . config('enums.system_permissions')['view'])
                ->name('hr.employees.files');
            Route::get('/employees/{id}/view', 'EmployeeController@show')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['employees'] . ',' . config('enums.system_permissions')['view'])
                ->name('hr.employees.details');
            Route::get('/employees/{id}/edit', 'EmployeeController@edit')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['employees'] . ',' . config('enums.system_permissions')['view'])
                ->name('hr.employees.edit.view');
            Route::post('/employees/add', 'EmployeeController@store')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['employees'] . ',' . config('enums.system_permissions')['create'])
                ->name('hr.employees.add');
            Route::post('/employees/edit', 'EmployeeController@update')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['employees'] . ',' . config('enums.system_permissions')['edit'])
                ->name('hr.employees.edit');
            Route::get('/employees/delete/{id}', 'EmployeeController@delete')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['employees'] . ',' . config('enums.system_permissions')['delete'])
                ->name('hr.employees.delete');
            Route::post('/employees/files', 'EmployeeController@uploadEmployeeFile')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['files'] . ',' . config('enums.system_permissions')['create'])
                ->name('hr.employees.files.upload');

            Route::post('/appraisal/employee/{id}', 'EmployeeController@employee_appraisal')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['employees'] . ',' . config('enums.system_permissions')['edit'])
                ->name('hr.employee.appraisal');

            Route::post('/disciplinary-action/employee/{id}', 'EmployeeController@employee_disciplinary')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['employees'] . ',' . config('enums.system_permissions')['edit'])
                ->name('hr.employee.disciplinary-action');

            //leave mgmt
            Route::get('/leaves', 'EmployeeLeaveController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['leave'] . ',' . config('enums.system_permissions')['view'])
                ->name('hr.leaves.show');
            Route::get('/leaves/download/{type}', 'EmployeeLeaveController@export_registered_employees_leaves')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['leave'] . ',' . config('enums.system_permissions')['download'])
                ->name('hr.leaves.download');
            Route::get('/leaves/{id}/view', 'EmployeeLeaveController@show')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['leave'] . ',' . config('enums.system_permissions')['view'])
                ->name('hr.leaves.details');
            Route::post('/leaves/add', 'EmployeeLeaveController@store')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['leave'] . ',' . config('enums.system_permissions')['create'])
                ->name('hr.leaves.add');
            Route::post('/leaves/edit', 'EmployeeLeaveController@update')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['leave'] . ',' . config('enums.system_permissions')['edit'])
                ->name('hr.leaves.edit');
            Route::get('/leaves/{id}/change', 'EmployeeLeaveController@change')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['leave'] . ',' . config('enums.system_permissions')['edit'])
                ->name('hr.leaves.change');
            Route::get('/leaves/delete/{id}', 'EmployeeLeaveController@delete')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['leave'] . ',' . config('enums.system_permissions')['delete'])
                ->name('hr.leaves.delete');
            //recruitment
            Route::get('/recruitments', 'RecruitmentController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['recruitment'] . ',' . config('enums.system_permissions')['view'])
                ->name('hr.recruitments.show');
            Route::get('/recruitment/{id}', 'RecruitmentController@edit')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['recruitment'] . ',' . config('enums.system_permissions')['view'])
                ->name('hr.recruitments.detail');
            Route::post('/recruitment/add', 'RecruitmentController@store')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['recruitment'] . ',' . config('enums.system_permissions')['create'])
                ->name('hr.recruitment.add');
            Route::post('/recruitment/edit', 'RecruitmentController@update')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['recruitment'] . ',' . config('enums.system_permissions')['edit'])
                ->name('hr.recruitment.edit');
            Route::get('/recruitment/{id}/delete', 'RecruitmentController@delete')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['recruitment'] . ',' . config('enums.system_permissions')['delete'])
                ->name('hr.recruitment.delete');
            Route::get('/recruitment/{id}/close', 'RecruitmentController@close')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['recruitment'] . ',' . config('enums.system_permissions')['edit'])
                ->name('hr.recruitment.close');
            Route::get('/recruitment/{id}/applications', 'RecruitmentController@applications')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['recruitment'] . ',' . config('enums.system_permissions')['create'])
                ->name('hr.recruitment.applications');
            // Employee Payroll
            Route::get('/employees/{employeeId}/salary', 'EmployeePayrollController@salary')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['payroll'] . ',' . config('enums.system_permissions')['view'])
                ->name('hr.employees.salary');
            Route::post('/employees/set/salary', 'EmployeePayrollController@updateHasBenefits')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['payroll'] . ',' . config('enums.system_permissions')['create'])
                ->name('hr.employees.updateHasBenefits');
            Route::post('/employees/set/allowance', 'EmployeePayrollController@setAllowance')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['payroll'] . ',' . config('enums.system_permissions')['create'])
                ->name('hr.employees.setallowance');
            Route::post('/employees/update/allowance', 'EmployeePayrollController@updateAllowance')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['payroll'] . ',' . config('enums.system_permissions')['edit'])
                ->name('hr.employees.updateallowance');
            Route::get('/employees/delete/{id}/allowance', 'EmployeePayrollController@deleteAllowance')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['payroll'] . ',' . config('enums.system_permissions')['delete'])
                ->name('hr.employees.deleteallowance');
            Route::get('/employees/{id}/benefits', 'EmployeePayrollController@editBenefit')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['payroll'] . ',' . config('enums.system_permissions')['edit'])
                ->name('hr.employees.editbenefit');

            Route::get('/payroll', 'EmployeePayrollController@payroll')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['payroll'] . ',' . config('enums.system_permissions')['view'])
                ->name('hr.employees.payroll');
            Route::get('/payroll/{payroll_id}', 'EmployeePayrollController@payrollDetails')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['payroll'] . ',' . config('enums.system_permissions')['view'])
                ->name('hr.employees.payroll.details');
            Route::post('/employees/payroll/generate', 'EmployeePayrollController@generatePayroll')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['payroll'] . ',' . config('enums.system_permissions')['create'])
                ->name('hr.employees.generatepayroll');
            //            Route::get('/employees/payroll/{payslip_id}/payslip', 'EmployeePayrollController@payslip')
            //                ->middleware('module_permission:'.config('enums.system_modules')['HR Management']['payroll'].','.config('enums.system_permissions')['view'])
            //                ->name('hr.employees.payslip.pdf');
            Route::get('/employee/{id}/payslip', 'EmployeePayrollController@payslip')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['payroll'] . ',' . config('enums.system_permissions')['view'])
                ->name('hr.employee.payslip.pdf');

            Route::get('/payrolls/department', 'EmployeePayrollController@departmentPayroll')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['department_payroll'] . ',' . config('enums.system_permissions')['view'])
                ->name('hr.employees.payroll.department');

            Route::post('/employee/import', 'EmployeeController@bulkImportEmployees')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['employees'] . ',' . config('enums.system_permissions')['create'])
                ->name('hr.employee.bulk.import');

            //downloads
            Route::get('/employees/download-employees/{type}', 'EmployeeController@export_employees')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['employees'] . ',' . config('enums.system_permissions')['download'])
                ->name('hr.employee.downloads');

            Route::get('/employees/payroll-summary/{type}', 'EmployeePayrollController@download_payroll_summary')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['payroll'] . ',' . config('enums.system_permissions')['download'])
                ->name('hr.payroll-summary.download');

            Route::get('/department/payroll-summary/{type}', 'EmployeePayrollController@download_department_payroll_summary')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['department_payroll'] . ',' . config('enums.system_permissions')['download'])
                ->name('hr.employees.payroll.department.download');

            Route::post('/employee/advance-deduction', 'EmployeePayrollController@addAdvanceDeductions')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['payroll'] . ',' . config('enums.system_permissions')['create'])
                ->name('hr.employees.payroll.advance.deduction');

            Route::get('/employee/advance-deduction/{id}/details', 'EmployeePayrollController@advance_deduction_transactions')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['payroll'] . ',' . config('enums.system_permissions')['view'])
                ->name('hr.employees.payroll.advance.deduction.details');

            Route::post('/employee/advance-deduction/{id}/details/download/{type}', 'EmployeePayrollController@download_advance_deduction_transactions')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['payroll'] . ',' . config('enums.system_permissions')['view'])
                ->name('hr.employees.payroll.advance.deduction.details.download');

            //reports
            Route::get('/reports', 'CooperativeHrReports@reports')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['dashboard'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.hr.reports');
            Route::get('/report/download', 'CooperativeHrReports@download_reports')
                ->middleware('module_permission:' . config('enums.system_modules')['HR Management']['dashboard'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.hr.report.download');
        });

        //mfg
        Route::middleware('module_gate:Manufacturing')->prefix('/manufacturing')->group(function () {
            Route::get('/products', 'ManufacturingController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['Manufacturing']['final_products'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.manufacturings.show');
            Route::get('/products/download/{type}', 'ManufacturingController@export_manufacturing_final_products')
                ->middleware('module_permission:' . config('enums.system_modules')['Manufacturing']['final_products'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.manufacturings.products.download');
            Route::get('/rawmaterials', 'ManufacturingController@allRawMaterials')
                ->middleware('module_permission:' . config('enums.system_modules')['Manufacturing']['raw_materials'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.manufacturings.rawmaterials');
            Route::get('/rawmaterials/download/{type}', 'ManufacturingController@export_raw_materials')
                ->middleware('module_permission:' . config('enums.system_modules')['Manufacturing']['raw_materials'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.manufacturings.rawmaterials.download');
            Route::post('raw-material/{id}/edit', 'ManufacturingController@edit_raw_material')
                ->middleware('module_permission:' . config('enums.system_modules')['Manufacturing']['raw_materials'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.manufacturing.raw-material.edit');
            Route::post('/add', 'ManufacturingController@store')
                ->middleware('module_permission:' . config('enums.system_modules')['Manufacturing']['final_products'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.manufacturing.add');
            Route::post('/{id}/edit', 'ManufacturingController@edit_final_product')
                ->middleware('module_permission:' . config('enums.system_modules')['Manufacturing']['final_products'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.manufacturing.final_product.edit');
            Route::get('/reports', 'ManufacturingController@getReports')
                ->middleware('module_permission:' . config('enums.system_modules')['Manufacturing']['reports'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.manufacturing.reports');
            Route::get('/reports/download/{type}', 'ManufacturingController@export_manufacturing_reports')
                ->middleware('module_permission:' . config('enums.system_modules')['Manufacturing']['reports'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.manufacturing.reports.download');
            Route::post('/store/raw-materials', 'ManufacturingController@storeMaterial')
                ->middleware('module_permission:' . config('enums.system_modules')['Manufacturing']['raw_materials'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.manufacturing.store.raw-materials');

            Route::get('/production', 'ManufacturingController@production')
                ->middleware('module_permission:' . config('enums.system_modules')['Manufacturing']['production'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.manufacturing.production');
            Route::get('/production/download/{type}', 'ManufacturingController@export_manufacturing_production')
                ->middleware('module_permission:' . config('enums.system_modules')['Manufacturing']['production'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.manufacturing.production.download');
            Route::post('/production/add', 'ManufacturingController@storeProduction')
                ->middleware('module_permission:' . config('enums.system_modules')['Manufacturing']['production'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.manufacturing.production.add');
            Route::get('/production/{productionId}/history/{id}/raw-materials', 'ManufacturingController@rawMaterials')
                ->middleware('module_permission:' . config('enums.system_modules')['Manufacturing']['raw_materials'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.manufacturing.production.raw-materials');

            Route::post('/production-history/{id}/raw-materials/add', 'ManufacturingController@addProductionRawMaterials')
                ->middleware('module_permission:' . config('enums.system_modules')['Manufacturing']['production'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.manufacturing.production.materials.add');

            Route::post('/production-history/{id}/raw-materials/edit', 'ManufacturingController@editProductionRawMaterials')
                ->middleware('module_permission:' . config('enums.system_modules')['Manufacturing']['production'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.manufacturing.production.materials.edit');

            Route::get('/production/product/{id}/production-history', 'ManufacturingController@show_production_history')
                ->middleware('module_permission:' . config('enums.system_modules')['Manufacturing']['production'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.manufacturing.production-history');

            Route::get('/production/expired-stock', 'ManufacturingController@expired_stock')
                ->middleware('module_permission:' . config('enums.system_modules')['Manufacturing']['expired_stock'] . ',' . config('enums.system_permissions')['view'])
                ->name('manufacturing.production.expired-stock');

            Route::get('/production/expired-stock/download/{type}', 'ManufacturingController@expired_stock_download')
                ->middleware('module_permission:' . config('enums.system_modules')['Manufacturing']['expired_stock'] . ',' . config('enums.system_permissions')['download'])
                ->name('manufacturing.production.expired-stock.download');

            Route::get('/production_history/{id}/download/{type}', 'ManufacturingController@export_production_history')
                ->middleware('module_permission:' . config('enums.system_modules')['Manufacturing']['production'] . ',' . config('enums.system_permissions')['download'])
                ->name('manufacturing.production-history.download');

            Route::get('/production_history/{productionHistoryId}/raw_materials/download/{type}', 'ManufacturingController@export_production_raw_materials')
                ->middleware('module_permission:' . config('enums.system_modules')['Manufacturing']['production'] . ',' . config('enums.system_permissions')['download'])
                ->name('manufacturing.production-history-raw-materials.download');
        });

        /******************** PROCUREMENT ***********************/
        Route::middleware('module_gate:Sales')->prefix('/procurement')->group(function () {

            // store
            Route::get('/stores', 'ManufacturingController@manufacturing_stores')
                ->middleware('module_permission:' . config('enums.system_modules')['Procurement']['store'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.manufacturing.stores');
            Route::post('/store/add', 'ManufacturingController@add_manufacturing_stores')
                ->middleware('module_permission:' . config('enums.system_modules')['Procurement']['store'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.manufacturing.store.add');
            Route::post('/store/{id}/edit', 'ManufacturingController@edit_manufacturing_stores')
                ->middleware('module_permission:' . config('enums.system_modules')['Procurement']['store'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.manufacturing.store.edit');
            Route::get('/store/download/{type}', 'ManufacturingController@export_stores')
                ->middleware('module_permission:' . config('enums.system_modules')['Procurement']['store'] . ',' . config('enums.system_permissions')['download'])
                ->name('manufacturing.store.download');
            Route::get('/store/{storeId}/data', 'ManufacturingController@get_by_store')
                ->middleware('module_permission:' . config('enums.system_modules')['Procurement']['store'] . ',' . config('enums.system_permissions')['view'])
                ->name('manufacturing.data-by-store');

            Route::get('/store/{storeId}}/data/production_history/download/{type}', 'ManufacturingController@export_production_history_by_store')
                ->middleware('module_permission:' . config('enums.system_modules')['Procurement']['store'] . ',' . config('enums.system_permissions')['download'])
                ->name('manufacturing.store.production-history.download');

            Route::get('/store/{storeId}}/data/supplies/download/{type}', 'ManufacturingController@export_supplies_by_store')
                ->middleware('module_permission:' . config('enums.system_modules')['Procurement']['store'] . ',' . config('enums.system_permissions')['download'])
                ->name('manufacturing.store.supplies.download');
            // suppliers
            Route::get('/suppliers', 'SupplierController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['Procurement']['suppliers'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.suppliers');
            Route::get('/download/{type}', 'SupplierController@export_suppliers')
                ->middleware('module_permission:' . config('enums.system_modules')['Procurement']['suppliers'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.suppliers.download');
            Route::post('/supplier/add', 'SupplierController@store')
                ->middleware('module_permission:' . config('enums.system_modules')['Procurement']['suppliers'] . ',' . config('enums.system_permissions')['create'])
                ->name('supplier.add');
            Route::get('/suppliers/get', 'SupplierController@getSuppliers');

            // supply
            Route::get('/supplies', 'ManufacturingController@manufacturing_supply')
                ->middleware('module_permission:' . config('enums.system_modules')['Procurement']['purchase_orders'] . ',' . config('enums.system_permissions')['view'])
                ->name('manufacturing.supplies');
            Route::post('/supply/add', 'ManufacturingController@add_manufacturing_supply')
                ->middleware('module_permission:' . config('enums.system_modules')['Procurement']['purchase_orders'] . ',' . config('enums.system_permissions')['create'])
                ->name('supply.add');
            Route::post('/store/{id}/edit', 'ManufacturingController@edit_manufacturing_stores')
                ->middleware('module_permission:' . config('enums.system_modules')['Procurement']['purchase_orders'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.manufacturing.store.edit');
            Route::get('/supply/{raw_material_id}/details', 'ManufacturingController@manufacturing_supply_details')
                ->middleware('module_permission:' . config('enums.system_modules')['Procurement']['purchase_orders'] . ',' . config('enums.system_permissions')['view'])
                ->name('manufacturing.supply.details');

            Route::post('/supply-history/{id}/pay', 'ManufacturingController@mark_purchase_orders_as_paid')
                ->middleware('module_permission:' . config('enums.system_modules')['Procurement']['purchase_orders'] . ',' . config('enums.system_permissions')['edit'])
                ->name('manufacturing.supply.history.pay');

            Route::post('/supply-history/{id}/edit', 'ManufacturingController@edit_raw_material_supplies')
                ->middleware('module_permission:' . config('enums.system_modules')['Procurement']['purchase_orders'] . ',' . config('enums.system_permissions')['edit'])
                ->name('manufacturing.supply.history.edit');

            Route::get('/supply-history/{id}/receive-goods', 'ManufacturingController@mark_goods_as_recieved')
                ->middleware('module_permission:' . config('enums.system_modules')['Procurement']['purchase_orders'] . ',' . config('enums.system_permissions')['edit'])
                ->name('manufacturing.supply.history.mark_goods_as_recieved');

            Route::get('/supply/download/{type}', 'ManufacturingController@export_supplies')
                ->middleware('module_permission:' . config('enums.system_modules')['Procurement']['purchase_orders'] . ',' . config('enums.system_permissions')['download'])
                ->name('manufacturing.supply.download');

            Route::get('/supply/{raw_Material_id}/details/download/{type}', 'ManufacturingController@export_supply_details')
                ->middleware('module_permission:' . config('enums.system_modules')['Procurement']['purchase_orders'] . ',' . config('enums.system_permissions')['download'])
                ->name('manufacturing.supply-details.download');

            Route::get('/supplier/{supplierId}/supplies', 'ManufacturingController@supplier_supplies')
                ->middleware('module_permission:' . config('enums.system_modules')['Procurement']['purchase_orders'] . ',' . config('enums.system_permissions')['view'])
                ->name('manufacturing.supplier.supplies');

            Route::get('/supplier/{supplierId}/supplies/download/{type}', 'ManufacturingController@export_supplier_supplies')
                ->middleware('module_permission:' . config('enums.system_modules')['Procurement']['purchase_orders'] . ',' . config('enums.system_permissions')['download'])
                ->name('manufacturing.supplier.supplies.download');

            Route::get('/purchase-order/{supply_history_id}/receipt', 'ManufacturingController@download_purchase_orders_receipt')
                ->middleware('module_permission:' . config('enums.system_modules')['Procurement']['purchase_orders'] . ',' . config('enums.system_permissions')['download'])
                ->name('manufacturing.purchase_order.receipts.download');
        });

        /*************
         *  SALES
         *************/
        Route::middleware('module_gate:Sales')->prefix('/sales')->group(function () {
            Route::get('/pos', 'SalesController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['Sales']['invoice'] . ',' . config('enums.system_permissions')['view'])
                ->name('sales.pos');
            Route::get('/void-ivoices', 'SalesController@voidedInvoices')
                ->middleware('module_permission:' . config('enums.system_modules')['Sales']['void_invoices'] . ',' . config('enums.system_permissions')['view'])
                ->name('sales.pos.void-invoices');
            Route::get('/quotation', 'SalesController@quotationView')
                ->middleware('module_permission:' . config('enums.system_modules')['Sales']['quotation'] . ',' . config('enums.system_permissions')['view'])
                ->name('sales.quotation');
            Route::get('/pos/{sale_id}/sale-items', 'SalesController@items')
                ->middleware('module_permission:' . config('enums.system_modules')['Sales']['invoice'] . ',' . config('enums.system_permissions')['create'])
                ->name('sales.pos.items');
            Route::get('/pos/{sale_id}/sale-quotation', 'SalesController@quotation')
                ->middleware('module_permission:' . config('enums.system_modules')['Sales']['quotation'] . ',' . config('enums.system_permissions')['view'])
                ->name('sales.pos.quotation');
            Route::get('/pos/{sale_id}/sale-quotation-pdf', 'SalesController@quotationPdf')
                ->middleware('module_permission:' . config('enums.system_modules')['Sales']['quotation'] . ',' . config('enums.system_permissions')['download'])
                ->name('sales.pos.quotation.pdf');
            Route::get('/pos/{sale_id}/sale-quotation-mail', 'SalesController@mailPdf')
                ->middleware('module_permission:' . config('enums.system_modules')['Sales']['quotation'] . ',' . config('enums.system_permissions')['download'])
                ->name('sales.pos.quotation.mail');
            Route::get('/pos/{sale_id}/sale-invoice-pdf', 'SalesController@invoicePdf')
                ->middleware('module_permission:' . config('enums.system_modules')['Sales']['invoice'] . ',' . config('enums.system_permissions')['download'])
                ->name('sales.pos.invoice.pdf');
            Route::post('/pos/add', 'SalesController@storeSale')
                ->middleware('module_permission:' . config('enums.system_modules')['Sales']['invoice'] . ',' . config('enums.system_permissions')['create'])
                ->name('sales.pos.add');
            Route::post('/pos/add-item/{sale_id}', 'SalesController@addSaleItems')
                ->middleware('module_permission:' . config('enums.system_modules')['Sales']['invoice'] . ',' . config('enums.system_permissions')['create'])
                ->name('sales.pos.add.item');
            Route::post('/pos/delete-item/{itemId}', 'SalesController@deleteSaleItem')
                ->middleware('module_permission:' . config('enums.system_modules')['Sales']['invoice'] . ',' . config('enums.system_permissions')['delete'])
                ->name('sales.pos.delete.item');
            Route::post('/pos/update-discount/{saleId}', 'SalesController@updateDiscount')
                ->middleware('module_permission:' . config('enums.system_modules')['Sales']['invoice'] . ',' . config('enums.system_permissions')['delete'])
                ->name('sales.pos.update.discount');
            Route::post('/pos/update-price-quantity/{itemId}', 'SalesController@updateSaleItemPriceAndQuantity')
                ->middleware('module_permission:' . config('enums.system_modules')['Sales']['invoice'] . ',' . config('enums.system_permissions')['delete'])
                ->name('sales.pos.update.price-quantity');

            Route::get('/manufactured-products', 'SalesController@getProductions');
            Route::get('/collected-products', 'SalesController@getProducts');
            //convert to invoice
            Route::get('/quotation/{sale_id}/to-invoice', 'SalesController@convertToInvoice')
                ->middleware('module_permission:' . config('enums.system_modules')['Sales']['invoice'] . ',' . config('enums.system_permissions')['create'])
                ->name('sales.quote.toinvoice');

            // deliver goods
            Route::get("/deliver-goods/{invoiceId}", 'SalesController@markGoodsAsDelivered')
                ->middleware('module_permission:' . config('enums.system_modules')['Sales']['invoice'] . ',' . config('enums.system_permissions')['edit'])
                ->name('sales.delivery');

            //pay
            Route::get('/pos/{sale_id}/payments', 'SalesController@payments')
                ->middleware('module_permission:' . config('enums.system_modules')['Sales']['invoice'] . ',' . config('enums.system_permissions')['view'])
                ->name('sales.pos.invoice.payments');
            Route::post('/pos/invoice/pay', 'SalesController@pay')
                ->middleware('module_permission:' . config('enums.system_modules')['Sales']['invoice'] . ',' . config('enums.system_permissions')['edit'])
                ->name('sales.invoice.pay');
            Route::get('/pos/{sale_id}/payment-receipt', 'SalesController@invoicePayReceipt')
                ->middleware('module_permission:' . config('enums.system_modules')['Sales']['quotation'] . ',' . config('enums.system_permissions')['download'])
                ->name('payment.receipt.pdf');
            //voide sales
            Route::get('/pos/{sale_id}/void', 'SalesController@voidSale')
                ->middleware('module_permission:' . config('enums.system_modules')['Sales']['quotation'] . ',' . config('enums.system_permissions')['edit'])
                ->name('sales.void');
            //reports
            Route::get('/reports', 'SalesReportController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['Sales']['reports'] . ',' . config('enums.system_permissions')['view'])
                ->name('sales.reports');

            // exports
            Route::post("/export/{type?}", 'SalesController@export_report_sale')
                ->middleware('module_permission:' . config('enums.system_modules')['Sales']['invoice'] . ',' . config('enums.system_permissions')['download'])
                ->name('sales.export');
            Route::post("/export/quote/{type}", 'SalesController@export_report_quotation')
                ->middleware('module_permission:' . config('enums.system_modules')['Sales']['quotation'] . ',' . config('enums.system_permissions')['download'])
                ->name('sales.quote.export');

            //returned items
            Route::get("returned-items", 'SalesController@returned_items')
                ->middleware('module_permission:' . config('enums.system_modules')['Sales']['returned_items'] . ',' . config('enums.system_permissions')['view'])
                ->name('sales.returned-items');
            Route::post("load-sale-items/{saleId}", 'SalesController@get_sale_items')
                ->middleware('module_permission:' . config('enums.system_modules')['Sales']['returned_items'] . ',' . config('enums.system_permissions')['view'])
                ->name('sales.load-sale-items');

            Route::post("record-returned-items", 'SalesController@record_returned_items')
                ->middleware('module_permission:' . config('enums.system_modules')['Sales']['returned_items'] . ',' . config('enums.system_permissions')['create'])
                ->name('record.returned.items');
            Route::post("record-returned-items/{type?}", 'SalesController@export_returned_goods')
                ->middleware('module_permission:' . config('enums.system_modules')['Sales']['returned_items'] . ',' . config('enums.system_permissions')['download'])
                ->name('export.returned.items');
        });

        /*************
         *  Customer management
         *************/
        Route::middleware('module_gate:Customer Management')->prefix('/customer')->group(function () {
            Route::get('/registered', 'CustomerController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['Customer Management']['customers'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.customers');
            Route::get('/download/{type}', 'CustomerController@export_customers')
                ->middleware('module_permission:' . config('enums.system_modules')['Customer Management']['customers'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.customers.download');
            Route::post('/pos/add', 'CustomerController@store')
                ->middleware('module_permission:' . config('enums.system_modules')['Customer Management']['customers'] . ',' . config('enums.system_permissions')['create'])
                ->name('customer.add');
            Route::get('/get', 'CustomerController@getCustomers');
        });

        /*************
         *  Financial Products
         *************/
        Route::middleware('module_gate:Financial Products')->prefix('/financial-products')->group(function () {
            Route::get('/loan-configs', 'ConfigsController@loan_configs')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['loan_products'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.loan_configs');
            Route::post('/loan-config', 'ConfigsController@loan_add')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['loan_products'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.loan_config.add');
            Route::post('/edit/loan-config/{id}', 'ConfigsController@edit_loan_saving_types')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['loan_products'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.loan_type.edit');
            Route::get('/saving-types', 'ConfigsController@savings_types')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['saving_types'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.saving_types');
            Route::post('/saving-type', 'ConfigsController@saving_type_add')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['saving_types'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.saving_type.add');
            Route::post('/edit/saving-type/{id}', 'ConfigsController@edit_saving_types')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['saving_types'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.saving_type.edit');
            Route::get('/loaned-farmers', 'FinancialProductController@loans')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['loan_application'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.loaned-farmers');
            Route::post('/loan/request', 'Farmer\LoansController@requestLoan')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['loan_application'] . ',' . config('enums.system_permissions')['create'])
                ->name('admin.loan.request');
            Route::post('/loan/request/{farmer_id}/{has_farm_tools}/limit', 'FinancialProductController@farmer_limit')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['loan_application'] . ',' . config('enums.system_permissions')['create'])
                ->name('admin.loan.farmer.limit');
            Route::get('/loan/{loan_id}/application-details', 'FinancialProductController@commercial_loan_details')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['loan_application'] . ',' . config('enums.system_permissions')['view'])
                ->name('admin.loan.farmer.commercial_loan_details');
            Route::get('/loan/{loan_id}/application-details/{newStatus}', 'FinancialProductController@update_commercial_loan_status')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['loan_application'] . ',' . config('enums.system_permissions')['edit'])
                ->name('admin.loan.farmer.commercial_loan_details.action');
            Route::get('/loans/installments/pay/{id}', 'Farmer\LoansController@payInstallmentView')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['loan_application'] . ',' . config('enums.system_permissions')['view'])
                ->name('admin.loans.pay-installment');
            Route::post('/loans/installments/pay', 'Farmer\LoansController@payInstallment')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['loan_application'] . ',' . config('enums.system_permissions')['edit'])
                ->name('admin.loan.pay-installment');

            Route::get('/farmer-savings', 'FinancialProductController@savings')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['current_savings'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.farmer-savings');
            Route::get('/loan/{loan_id}/installments', 'FinancialProductController@loan_installments')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['loan_application'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.farmer-loan_installments');
            Route::get('/dashboard', 'FinancialProductController@dashboard')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['dashboard'] . ',' . config('enums.system_permissions')['view'])
                ->name('financial_products.dashboard');
            Route::post('/dashboard/stats', 'FinancialProductController@financial_dashboard_starts')->name('financial_products.dashboard.stats');
            Route::post('/saving/add', 'FinancialProductController@admin_create_saving_account')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['current_savings'] . ',' . config('enums.system_permissions')['create'])
                ->name('financial_products.savings.add');
            Route::post('/saving/withdraw', 'FinancialProductController@admin_initiate_withdraw_from_saving_account')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['current_savings'] . ',' . config('enums.system_permissions')['edit'])
                ->name('financial_products.savings.withdraw');
            Route::get('/saving-account/{id}/statement', 'FinancialProductController@saving_installments')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['current_savings'] . ',' . config('enums.system_permissions')['view'])
                ->name('financial_products.savings.statement');
            Route::post('/saving-account/{farmerId}/matured', 'FinancialProductController@farmer_matured_saving_type')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['current_savings'] . ',' . config('enums.system_permissions')['view'])
                ->name('financial_products.matured-savings');
            Route::get('/loan/download/{type}', 'FinancialProductController@export_loaned_farmers')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['loan_application'] . ',' . config('enums.system_permissions')['download'])
                ->name('download.loan.report');
            Route::get('/saving/download/{type?}', 'FinancialProductController@export_farmer_savings')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['current_savings'] . ',' . config('enums.system_permissions')['download'])
                ->name('download.savings.report');
            Route::get('/saving/{savingId}/installment/download/{type?}', 'FinancialProductController@export_saving_installments')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['current_savings'] . ',' . config('enums.system_permissions')['download'])
                ->name('download.savings.installment.report');
            Route::get('/loan/{loandId}/installment/download/{type}', 'FinancialProductController@export_loan_installments')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['loan_application'] . ',' . config('enums.system_permissions')['download'])
                ->name('download.loan.installment.report');
            Route::get('/loan/defaulters', 'FinancialProductController@loan_defaulters')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['loan_defaulters'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.loan.defaulters');
            Route::get('/loan/repayments', 'FinancialProductController@loan_repayments')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['loan_repayments'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.loan.repayments');
            Route::get('/loan/defaulters/download/{type}', 'FinancialProductController@export_loan_defaulters')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['current_savings'] . ',' . config('enums.system_permissions')['download'])
                ->name('download.loan.defaulters.report');
            Route::get('/loan/repayments/download/{type}', 'FinancialProductController@export_loan_repayments')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['loan_repayments'] . ',' . config('enums.system_permissions')['download'])
                ->name('download.loan.repayments.report');
            Route::post('/loan/repay/{loanInstallmentdId}', 'FinancialProductController@repay_loan')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['loan_repayments'] . ',' . config('enums.system_permissions')['edit'])
                ->name('loan.installment.repay');
            Route::get('/loan/interest', 'FinancialProductController@interest')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['interest'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.loan.interest');
            Route::get('/loan/interest/download/{type}', 'FinancialProductController@export_interest')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['interest'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.interest.download');
            Route::get('/group-loan-types', 'FinancialProductController@groupLoanTypes')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['group_loan_type'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.group_loan_types');
            Route::post('/group-loan-type/create', 'FinancialProductController@addGroupLoanType')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['group_loan_type'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.group_loan_type.create');
            Route::get('/group-loans', 'FinancialProductController@groupLoans')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['group_loans'] . ',' . config('enums.system_permissions')['create'])
                ->name('admin.group.loans');
            Route::post('/group/loans/request', 'FinancialProductController@requestGroupLoan')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['loan_application'] . ',' . config('enums.system_permissions')['create'])
                ->name('admin.group.loan.request');
            Route::get('/group-loan/{id}/details', 'FinancialProductController@group_loan_details')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['loan_application'] . ',' . config('enums.system_permissions')['view'])
                ->name('admin.group.loan.details');
            Route::post('/group-loan/{id}/repay', 'FinancialProductController@groupLoanRepayment')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['loan_application'] . ',' . config('enums.system_permissions')['view'])
                ->name('admin.group.loan.repay');
            Route::get('/group-loan/{id}/repayment-history', 'FinancialProductController@group_loan_repayment_history')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['loan_application'] . ',' . config('enums.system_permissions')['view'])
                ->name('admin.group.loan.repayments');
            Route::get('/group-loan-config', 'FinancialProductController@group_loan_setting')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['group_loan_setting'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.group.loan.config');
            Route::post('/group-loan-setting/add', 'FinancialProductController@add_group_loan_config')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['group_loan_setting'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.group.loan.config.add');
            Route::post('/group-loan-setting/{id}/edit', 'FinancialProductController@edit_group_loan_setting')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['group_loan_setting'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.group.loan.config.edit');
            Route::get('/group-loan-repayments', 'FinancialProductController@group_loan_repayments')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['group_loan_repayments'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.group.loan.repayments');
            Route::get('/limit-rate-config', 'FinancialProductController@limit_config')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['limit_rate_setting'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.limit-rate.config');
            Route::post('/limit-rate-config/create', 'FinancialProductController@set_limit_config')
                ->middleware('module_permission:' . config('enums.system_modules')['Financial Products']['limit_rate_setting'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.limit-rate.config.create');
        });

        /**************************
         * Insurance
         **************************/
        Route::middleware('module_gate:Insurance Product')->prefix('/insurance')->group(function () {
            Route::get('/config/premium-adjustments', 'InsuranceController@configureInstallmentRate')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['premium_adjustments'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.insurance.config.premium-adjustments');
            Route::post('/config/premium-adjustment', 'InsuranceController@addConfigureInstallmentRate')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['premium_adjustments'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.insurance.config.premium-adjustment.add');
            Route::post('/config/premium-adjustment/{id}', 'InsuranceController@updateConfigureInstallmentRate')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['premium_adjustments'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.insurance.config.premium-adjustment.update');
            Route::get('/benefits', 'InsuranceController@benefits')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['product_benefits'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.insurance.benefits');
            Route::post('/benefit', 'InsuranceController@addBenefit')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['product_benefits'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.insurance.benefit.add');
            Route::get('/products', 'InsuranceController@products')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['product_premiums'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.insurance.products');
            Route::post('/product', 'InsuranceController@addProduct')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['product_premiums'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.insurance.product.add');
            Route::get('/valuations', 'InsuranceController@valuation')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['valuation'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.insurance.valuations');
            Route::post('/valuation', 'InsuranceController@addValuation')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['valuation'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.insurance.valuation.add');
            Route::get('/subscriptions', 'InsuranceController@subscriptions')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['insurance_subscription'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.insurance.subscriptions');
            Route::get('/subscriptions/download/{type}', 'InsuranceController@export_insurance_subscriptions')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['insurance_subscription'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.insurance.subscriptions.download');
            Route::post('/subscription', 'InsuranceController@newSubscription')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['insurance_subscription'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.insurance.subscription.add');
            Route::post('/subscription/{id}/edit', 'InsuranceController@editInsuranceSubscription')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['insurance_subscription'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.insurance.subscription.edit');
            Route::get('/subscription/{subscription_id}/installment', 'InsuranceController@insuranceInstallments')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['insurance_subscription'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.insurance.subscription.installments');
            Route::post('/{farmer}/valuations', 'InsuranceController@getValuationByFarmer')->name('cooperative.subscription.farmer.valuations');
            Route::post('/{id}/dependant', 'InsuranceController@addInsuranceDependant')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['insurance_subscription'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.subscription.dependant');
            Route::get('/{id}/dependants', 'InsuranceController@dependants')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['insurance_subscription'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.subscription.dependants');
            Route::post('/{id}/dependant/edit', 'InsuranceController@editDependants')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['insurance_subscription'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.subscription.dependant.edit');
            Route::post('/installment/{id}/pay', 'InsuranceController@pay_installments')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['insurance_subscription'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.subscription.installment.pay');
            Route::get('/claim-limits', 'InsuranceController@claim_limits')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['product_limit'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.claim-limits');
            Route::post('/claim-limit', 'InsuranceController@add_claim_limit')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['product_limit'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.claim-limit.add');
            Route::post('/claim-limit/{id}', 'InsuranceController@edit_claim_limit')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['product_limit'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.claim-limit.edit');
            Route::post('/subscription-by-farmer/{farmer_id}', 'InsuranceController@getSubscriptionsByFarmer')->name('cooperative.subscription-by-farmer');
            Route::get('/claims', 'InsuranceController@claims')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['claims'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.insurance.claims');
            Route::get('/claims/download/{type}', 'InsuranceController@export_insurance_claims_mngt')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['claims'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.insurance.claims.download');
            Route::post('/claim', 'InsuranceController@addClaim')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['claims'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.insurance.claim.add');
            Route::post('/claim/{id}/status', 'InsuranceController@updateClaimStatus')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['claims'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.insurance.claim.updated-status');
            Route::get('/claim/{id}/status-transitions', 'InsuranceController@claim_status_transition')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['claims'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.insurance.claim.status_transitions');
            Route::post('/claim/{id}', 'InsuranceController@editClaim')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['claims'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.insurance.claim.edit');
            Route::get('/transaction-history', 'InsuranceController@insurance_transaction_history')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['reports'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.insurance.trxn-hisory');
            Route::get('/transaction-history/download/{type}', 'InsuranceController@export_insurance_transaction_history')
                ->middleware('module_permission:' . config('enums.system_modules')['Insurance Product']['reports'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.insurance.trxn-hisory.download');
        });

        /*************
         *  mini-dashboards
         *************/

        Route::get('products/mini-dashboard', 'ProductManagementDashboardController@index')
            ->middleware('module_permission:' . config('enums.system_modules')['Product Management']['dashboard'] . ',' . config('enums.system_permissions')['view'])
            ->name('cooperative.product-mini-dashboard');
        Route::post('products/mini-dashboard/data', 'ProductManagementDashboardController@get_stats')->name('cooperative.product-mini-dashboard.stats');
        Route::get('/farm/dashboard', 'CowBreedMiniDashboardController@index')
            ->middleware('module_permission:' . config('enums.system_modules')['Farm Management']['dashboard'] . ',' . config('enums.system_permissions')['view'])
            ->name('cooperative.farm.mini-dashboard');
        Route::post('/farm/dashboard/stats', 'CowBreedMiniDashboardController@stats')->name('cooperative.farm.mini-dashboard.stats');
        Route::get('/farmer/dashboard', 'FarmerManagementMiniDashboardController@index')
            ->middleware('module_permission:' . config('enums.system_modules')['Farmer CRM']['dashboard'] . ',' . config('enums.system_permissions')['view'])
            ->name('cooperative.farmer.mini-dashboard');
        Route::post('/farmer/dashboard/stats', 'FarmerManagementMiniDashboardController@stats')->name('cooperative.farmer.mini-dashboard.stats');

        /*************
         *  User Management
         *************/
        Route::middleware('module_gate:User Management')->prefix('/user-management')->group(function () {
            Route::get('/roles', 'UserManagementController@roles')
                ->middleware('module_permission:' . config('enums.system_modules')['User Management']['roles'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.roles');
            Route::post('/role', 'UserManagementController@add_role')
                ->middleware('module_permission:' . config('enums.system_modules')['User Management']['roles'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.role.add');
            Route::post('/role/{id}/edit', 'UserManagementController@edit_role')
                ->middleware('module_permission:' . config('enums.system_modules')['User Management']['roles'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.role.edit');
            Route::post('/role/{id}/delete', 'UserManagementController@delete_role')
                ->middleware('module_permission:' . config('enums.system_modules')['User Management']['roles'] . ',' . config('enums.system_permissions')['delete'])
                ->name('cooperative.role.delete');
            Route::get('/role-management', 'UserManagementController@role_management')
                ->middleware('module_permission:' . config('enums.system_modules')['User Management']['role_management'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.role-management');
            Route::post('/role-management/assign-role', 'UserManagementController@assign_roles')
                ->middleware('module_permission:' . config('enums.system_modules')['User Management']['role_management'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.role-management.assign-roles');
            Route::post('/role-management/{emp}/{role}/revoke', 'UserManagementController@revoke_role')
                ->middleware('module_permission:' . config('enums.system_modules')['User Management']['role_management'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.role-management.revoke-roles');
            Route::get('/module/role-management', 'UserManagementController@module_management')
                ->middleware('module_permission:' . config('enums.system_modules')['User Management']['module_management'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.module-management');
            Route::post('/module/role-management/assign-role', 'UserManagementController@module_assign_role')
                ->middleware('module_permission:' . config('enums.system_modules')['User Management']['module_management'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.role-management.module-assign-roles');
            Route::post('/module/role-management/{module}/{role}/revoke', 'UserManagementController@module_revoke_role')
                ->middleware('module_permission:' . config('enums.system_modules')['User Management']['module_management'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.role-management.module-revoke-roles');
            Route::get('/sub-modules/{id}', 'UserManagementController@getSubmodulesByModuleId')->name('cooperative.sub-modules.by-module-id');
            Route::get('/permissions', 'UserManagementController@getPermissions')
                ->middleware('module_permission:' . config('enums.system_modules')['User Management']['permissions'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.permissions');
            Route::post('/permissions/add', 'UserManagementController@addPermissions')
                ->middleware('module_permission:' . config('enums.system_modules')['User Management']['permissions'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.permissions.add');
            Route::post('/permissions/{id}/edit', 'UserManagementController@editPermissions')
                ->middleware('module_permission:' . config('enums.system_modules')['User Management']['permissions'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.permissions.edit');
            Route::get('/role-permissions', 'UserManagementController@rolePermission')
                ->middleware('module_permission:' . config('enums.system_modules')['User Management']['role_permissions'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.role-permissions');
            Route::post('/role-permission', 'UserManagementController@addRolePermission')
                ->middleware('module_permission:' . config('enums.system_modules')['User Management']['role_permissions'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.role-permissions.add');
            Route::post('/role-permission/{id}/edit', 'UserManagementController@editRolePermission')
                ->middleware('module_permission:' . config('enums.system_modules')['User Management']['role_permissions'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.role-permissions.edit');
            Route::get('/activity-log', 'UserManagementController@activityLog')
                ->middleware('module_permission:' . config('enums.system_modules')['User Management']['activity_log'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.activity_log');

            Route::get('/activity-log/download/{type}/{employee?}/{dates?}', 'UserManagementController@export_audit_logs')
                ->middleware('module_permission:' . config('enums.system_modules')['User Management']['activity_log'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.activity_log.download');
        });
    });


    /*************
     *  Accounting
     *************/
    Route::middleware('module_gate:Accounting')->prefix('/accounting')->group(function () {
        Route::prefix('/wallet')->group(function () {
            Route::get('', 'WalletController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['wallet'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.wallet');
            Route::get('/loaned-farmers', 'WalletController@get_loaned_farmers')
                ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['wallet'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.wallet.get_loaned_farmers');
            Route::get('/pending-payments', 'WalletController@get_farmer_pending_payments')
                ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['wallet'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.wallet.pending_payments');
        });

        Route::prefix('/reports')
            ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['reports'] . ',' . config('enums.system_permissions')['view'])
            ->group(function () {
                Route::get('', 'AccountingController@index')->name('cooperative.accounting.reports');
                Route::get('/download/{type}', 'AccountingController@export_accounting_reports')
                    ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['reports'] . ',' . config('enums.system_permissions')['download'])
                    ->name('cooperative.accounting.reports.download');
                Route::get('/balance_sheet/{period}', 'StatementsController@balanceSheet')
                    ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['reports'] . ',' . config('enums.system_permissions')['download'])
                    ->name('cooperative.reports.balance_sheet');
                Route::get('/trial_balance/{period}', 'StatementsController@trialBalance')
                    ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['reports'] . ',' . config('enums.system_permissions')['download'])
                    ->name('cooperative.reports.trial_balance');
                Route::get('/income_statement/{period}', 'StatementsController@incomeStatement')
                    ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['reports'] . ',' . config('enums.system_permissions')['download'])
                    ->name('cooperative.reports.income_statement');
                Route::get('/budget_vs_actual/{period}', 'StatementsController@getBudgetVsActual')
                    ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['reports'] . ',' . config('enums.system_permissions')['download'])
                    ->name('cooperative.reports.budget_vs_actual');
                Route::get('/account_payables_summary/{period}', 'StatementsController@getAccountPayablesSummary')
                    ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['reports'] . ',' . config('enums.system_permissions')['download'])
                    ->name('cooperative.reports.account_payables_summary');
                Route::get('/account_receivables_summary/{period}', 'StatementsController@getAccountReceivablesSummary')
                    ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['reports'] . ',' . config('enums.system_permissions')['download'])
                    ->name('cooperative.reports.account_receivables_summary');

                Route::get('/farmer_consolidated/{period}', 'StatementsController@getFarmerConsolidatedReport')
                    ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['reports'] . ',' . config('enums.system_permissions')['download'])
                    ->name('cooperative.reports.farmer_consolidated');
                Route::get('/cooperative_consolidated/{period}', 'StatementsController@getCooperativeConsolidatedReport')
                    ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['reports'] . ',' . config('enums.system_permissions')['download'])
                    ->name('cooperative.reports.cooperative_consolidated');

                Route::get('/data', 'StatementsController@index')->name('cooperative.reports.data');
                Route::get('/show-ledger-report/{financial_period}/{ledger_account}', 'StatementsController@show_ledger_reports')
                    ->name('cooperative.reports.show_ledger_reports');
                Route::post('/print-ledger-report/{financial_period}/{ledger_account}', 'StatementsController@print_ledger_reports')
                    ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['reports'] . ',' . config('enums.system_permissions')['download'])
                    ->name('cooperative.reports.print_ledger_reports');
                Route::get('/show-profit-loss-report/{financial_period}', 'StatementsController@show_profit_loss_reports')
                    ->name('cooperative.reports.balance_profit_loss');
                Route::post('/print-profit-loss-report/{financial_period}', 'StatementsController@print_profit_loss_reports')
                    ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['reports'] . ',' . config('enums.system_permissions')['download'])
                    ->name('cooperative.reports.print_profit_loss_reports');
            });
        Route::middleware('financial_period')->group(function () {
            Route::get('/payments', 'WalletController@initiate_payments')->name('cooperative.wallet.payments');
            Route::post('/pay-farmer', 'WalletController@pay_farmer')->name('cooperative.wallet.pay_farmer');
            Route::get('/download/{type}/farmer/{farmer}/payments', 'WalletController@download_payment_histories')->name('cooperative.wallet.download_payment_histories');
            Route::get('/show/farmer/{farmer}/payments', 'WalletController@show_payment_histories')->name('cooperative.wallet.show_payment_histories');
            Route::get('/charts_of_account', 'AccountingController@details')
                ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['charts_of_account'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.accounting.charts_of_account');
            Route::get('/charts_of_account/download/{type}', 'AccountingController@export_accounting_details')
                ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['charts_of_account'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.accounting.charts_of_account.download');
            Route::get('/reports/{id}', 'AccountingController@report_type')->name('cooperative.accounting.report_type');
            Route::post('/add-ledger', 'AccountingController@add_ledger_account')
                ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['charts_of_account'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.accounting.add-ledger');
            Route::post('/delete-ledger/{ledger_id}', 'AccountingController@delete_ledger_account')
                ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['charts_of_account'] . ',' . config('enums.system_permissions')['delete'])
                ->name('cooperative.accounting.delete-ledger');
            Route::post('/edit-ledger/{ledger_id}', 'AccountingController@edit_ledger_account')
                ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['charts_of_account'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.accounting.edit-ledger');
            Route::get('/journal_entries', 'AccountingController@get_accounting_transaction')
                ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['journal_entries'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.accounting.journal_entries');
            Route::post('/create_transaction', 'AccountingController@create_transaction')
                ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['journal_entries'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.accounting.create_transaction');
            Route::get('/transactions/entries/download/{type}', 'AccountingController@export_accounting_transactions_jornal_entries')
                ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['journal_entries'] . ',' . config('enums.system_permissions')['download'])
                ->name('cooperative.transactionsentry.download');
            Route::post('/get_the_next_ledger_code/{ledger_code}', 'AccountingController@get_the_next_ledger_code')
                ->name('cooperative.accounting.get_the_next_ledger_code');
            Route::get('/rules', 'AccountingRuleController@index')
                ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['accounting_rules'] . ',' . config('enums.system_permissions')['view'])
                ->name('cooperative.accounting.rules');
            Route::post('/rule/add', 'AccountingRuleController@store')
                ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['accounting_rules'] . ',' . config('enums.system_permissions')['create'])
                ->name('cooperative.accounting.rule.add');
            Route::post('/rule/{id}/edit', 'AccountingRuleController@edit')
                ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['accounting_rules'] . ',' . config('enums.system_permissions')['edit'])
                ->name('cooperative.accounting.rule.edit');
            Route::post('/rule/{id}/delete', 'AccountingRuleController@delete')
                ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['accounting_rules'] . ',' . config('enums.system_permissions')['delete'])
                ->name('cooperative.accounting.rule.delete');
        });

        Route::post('/close_financial_period/{financial_period_id}', 'AccountingController@close_financial_period')
            ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['reports'] . ',' . config('enums.system_permissions')['edit'])
            ->name('cooperative.accounting.close_financial_period');
        Route::get('/property', 'PropertyController@index')
            ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['asset'] . ',' . config('enums.system_permissions')['view'])
            ->name('cooperative.accounting.property.index');
        Route::post('/property/add', 'PropertyController@store')
            ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['asset'] . ',' . config('enums.system_permissions')['create'])
            ->name('cooperative.accounting.property.store');
        Route::post('/property/edit', 'PropertyController@update')
            ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['asset'] . ',' . config('enums.system_permissions')['edit'])
            ->name('cooperative.accounting.property.update');
        Route::post('/property/{id}/delete', 'PropertyController@deleteProperty')
            ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['asset'] . ',' . config('enums.system_permissions')['delete'])
            ->name('cooperative.accounting.property.delete');

        Route::get('/budget', 'BudgetController@index')
            ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['budget'] . ',' . config('enums.system_permissions')['view'])
            ->name('cooperative.accounting.budget.index');
        Route::post('/budget', 'BudgetController@store')
            ->middleware('module_permission:' . config('enums.system_modules')['Accounting']['budget'] . ',' . config('enums.system_permissions')['create'])
            ->name('cooperative.accounting.budget.store');
    });
});


//farmer routes
Route::middleware('role:farmer')->prefix('farmer')->group(function () {

    /****************
     * Collections
     *****************/
    Route::prefix('/collections')->group(function () {
        Route::get('/show', 'Farmer\CollectionController@farmerIndex')->name('farmer.collections.show');
        Route::get('/', 'Farmer\CollectionController@collections')->name('farmer.collections');
        Route::post('/', 'Farmer\CollectionController@addCollection')->name('farmer.collection.add');
        Route::get('/reports', 'Farmer\CollectionController@getFarmerReports')->name('farmer.collections.reports');
    });


    /****************
     * Farm Management
     *****************/
    Route::prefix('/farm')->group(function () {
        Route::get('/livestock', 'Farmer\FarmController@cows')->name('farm.livestock');
        Route::post('/livestock', 'Farmer\FarmController@add_livestock')->name('farm.livestock.add');
        Route::get('/breeds', 'Farmer\FarmController@breeds')->name('farm.breeds');
        Route::get('/farm-units', 'Farmer\FarmController@farm_units')->name('farm.farm-units');
        Route::get('/crops', 'Farmer\FarmController@crops')->name('farm.crops');
        Route::get('/crop-calendar-stages', 'Farmer\FarmController@crop_calendar_stages')->name('farm.crop-calendar-stages');
        Route::get('/crop-stages', 'Farmer\FarmController@farmer_crops')->name('farm.crop-stages');
        Route::post('/crop-stage', 'Farmer\FarmController@add_farmer_crop')->name('farm.crop-stages.add');
        Route::post('/crop-stage/{id}/tracker/add', 'Farmer\FarmController@add_farmer_crop_stages')->name('farm.crop-stages.tracker.add');
        Route::get('/crop-stages/tracker/{id}/cost_breakdown', 'Farmer\FarmController@get_cost_break_down')->name('farm.crop-stages.tracker.cost_breakdown');
        Route::post('/crop-stages/tracker/cost-break-down/{id}/edit', 'Farmer\FarmController@edit_cost_break_down')->name('farmer.farm.tracker.cost-break-down.edit');
        Route::post('/crop-stages/tracker/cost-break-down/{id}/add', 'Farmer\FarmController@add_new_cost')->name('farmer.farm.tracker.cost-break-down.add');
        Route::post('/crop-stages/tracker/cost-break-down/{id}/delete', 'Farmer\FarmController@delete_cost_break_down')->name('farmer.farm.tracker.cost-break-down.delete');

        Route::get('/crop-stages/farmer-crop/{id}/trackers/{type}', 'Farmer\FarmController@farmer_crop_stages')->name('farm.farmer-crop.trackers');
        Route::post('/farmer-crop/{id}/calendar-data', 'Farmer\FarmController@get_farmer_crop_calendar')->name('farm.crop-stages-calendar-data');
        Route::get('/expected-yields', 'Farmer\FarmController@expected_yields')->name('farm.expected-yields');
        Route::get('/my-yields', 'Farmer\FarmController@yields')->name('farm.yields');
    });

    /****************
     * Disease Management
     *****************/
    Route::prefix('/disease')->group(function () {
        Route::get('/mini-dashboard', 'Farmer\DiseaseController@dashboard_data')->name('disease.mini-dashboard');
        Route::post('/mini-dashboard/stats', 'Farmer\DiseaseController@stats')->name('disease.mini-dashboard.stats');
        Route::post('/mini-dashboard/disease_map_data', 'Farmer\DiseaseController@disease_map_data')->name('disease.mini-dashboard.disease_map_data');
        Route::get('/categories', 'Farmer\DiseaseController@categories')->name('disease.categories');
        Route::get('', 'Farmer\DiseaseController@disease')->name('diseases');
        Route::get('/cases', 'Farmer\DiseaseController@cases')->name('disease.cases');
        Route::post('/case/add', 'Farmer\DiseaseController@add_case')->name('disease.case.add');
        Route::post('/case/{id}/edit', 'Farmer\DiseaseController@edit_case')->name('disease.case.edit');
        Route::get('/case/{id}/bookings', 'Farmer\DiseaseController@case_bookings')->name('disease.case.bookings');
        Route::post('/case/{id}/book-vet', 'Farmer\DiseaseController@book')->name('disease.case.book-vet');
    });

    /****************
     * Vet Management
     *****************/
    Route::prefix('/vet')->group(function () {
        Route::get('/my-bookings/show', 'Farmer\VetController@index')->name('farmer.vet.my-bookings.show');
        Route::get('/bookings/fetch', 'Farmer\VetController@get_farmer_bookings')->name('farmer.vet.my-bookings.fetch');
        Route::post('/bookings/add', 'Farmer\VetController@add_booking')->name('farmer.vet.my-booking.add');
        Route::post('/bookings/{id}/edit', 'Farmer\VetController@edit_bookings')->name('farmer.vet.my-booking.edit');
        Route::post('/bookings/{id}/edit/status', 'Farmer\VetController@edit_booking_status')->name('farmer.vet.my-booking.edit.status');
    });

    /*************
     * WALLET
     *************/
    Route::prefix('/wallet')->group(function () {
        Route::get('/dashboard', 'Farmer\WalletController@index')->name('farmer.wallet.dashboard');
        Route::get('/transactions', 'Farmer\WalletController@transactions')->name('farmer.wallet.transactions');
        Route::post('/transactions/withdraw', 'MpesaController@b2cInit')->name('farmer.wallet.transactions.withdraw');
        Route::post('/transactions/deposit', 'MpesaController@lnmStkPush')->name('farmer.wallet.transactions.deposit');

        Route::get('/loans', 'Farmer\LoansController@index')->name('farmer.wallet.loans');
        Route::get('/loans/{loan_id}/details', 'Farmer\LoansController@details')->name('farmer.wallet.loans.details');
        Route::post('/loans/request', 'Farmer\LoansController@requestLoan')->name('farmer.wallet.loans.request');
        Route::get('/loan/{id}/installments', 'Farmer\LoansController@loan_installments')->name('farmer.loan.installments');
        Route::post('/loan/{id}/repay', 'Farmer\LoansController@repay_loan')->name('farmer.loan.installment.repay');

        Route::get('/savings', 'Farmer\SavingsController@index')->name('farmer.wallet.savings');
        Route::get('/saving/{id}/installments', 'Farmer\SavingsController@installments')->name('farmer.wallet.saving.installments');
        Route::post('/savings/add', 'Farmer\SavingsController@create_saving_account')->name('farmer.wallet.savings.add');
        Route::post('/savings/withdraw', 'Farmer\SavingsController@withdraw_from_saving_account')
            ->name('farmer.wallet.savings.withdraw');
        Route::post('/barchart-doughnutChart', 'Farmer\WalletController@bar_chart_data')->name('farmer.wallet.dashboard.barchart');
    });

    /*************
     * INSURANCE
     *************/
    Route::prefix('/insurance')->group(function () {
        Route::get('/payment-mode-adjustments', 'Farmer\InsuranceController@paymentModeAdjustments')->name('insurance.payment-mode-adjustments');
        Route::get('/product-benefits', 'Farmer\InsuranceController@benefits')->name('insurance.product-benefits');
        Route::get('/products', 'Farmer\InsuranceController@products')->name('insurance.products');
        Route::get('/my-valuations', 'Farmer\InsuranceController@valuations')->name('insurance.valuations');
        Route::get('/subscriptions', 'Farmer\InsuranceController@subscriptions')->name('insurance.subscriptions');
        Route::post('/subscription', 'Farmer\InsuranceController@newSubscription')->name('insurance.subscription');
        Route::get('/subscription/{id}/installments', 'Farmer\InsuranceController@insuranceInstallments')->name('insurance.subscription.installments');
        Route::post('/subscription/installment/{id}/pay', 'Farmer\InsuranceController@pay_installments')->name('insurance.subscription.installment.pay');
        Route::get('/products-limit', 'Farmer\InsuranceController@claimLimits')->name('insurance.products-limit');
        Route::get('/claims', 'Farmer\InsuranceController@claims')->name('insurance.claims');
        Route::post('/claim', 'Farmer\InsuranceController@addClaim')->name('insurance.claim.add');
        Route::post('/claim/{id}/edit', 'Farmer\InsuranceController@editClaim')->name('insurance.claim.edit');
        Route::get('/claim/{id}/status-transitions', 'Farmer\InsuranceController@claim_status_transition')->name('insurance.status-transitions');
        Route::get('/transaction-history', 'Farmer\InsuranceController@insurance_transaction_history')->name('insurance.transaction-history');
    });
});


//vets routes
Route::middleware('role:vet')->prefix('vet')->group(function () {
    Route::get('/my-bookings/show', 'Vet\ScheduleManagementController@index')->name('vet.my-bookings.show');
    Route::get('/bookings/fetch', 'Vet\ScheduleManagementController@get_vet_bookings')->name('vet.my-bookings.fetch');
    Route::post('/booking/add', 'Vet\ScheduleManagementController@add_booking')->name('vet.my-booking.add');
});

//farmer admin routes
Route::middleware('role:farmer|cooperative admin|admin')->prefix('profile')->group(function () {
    Route::get('/farmer/{id}/show', 'Farmer\ProfileController@show')->name('cooperative.farmer.profile');
    Route::get('/farmer/{id}/savings', 'Farmer\ProfileController@savings')->name('cooperative.farmer.savings');
    Route::get('/farmer/{id}/loans', 'Farmer\ProfileController@loans')->name('cooperative.farmer.loans');
    Route::get('/farmer/{farmer_id}/savings/download/{type}/', 'Farmer\ProfileController@export_savings')->name('cooperative.farmer.savings.download');
    Route::get('/farmer/{farmer_id}/loans/download/{type}/', 'Farmer\ProfileController@export_loans')->name('cooperative.farmer.loans.download');
    Route::get('/farmer/{farmer_id}/purchases', 'Farmer\ProfileController@purchases')->name('cooperative.farmer.purchases');
    Route::get('/farmer/{farmer_id}/purchases/download/{type}', 'Farmer\ProfileController@export_purchases')->name('cooperative.farmer.purchases.download');
});


Route::group(['prefix' => 'basic-ui'], function () {
    Route::get('accordions', function () {
        return view('pages.basic-ui.accordions');
    });
    Route::get('buttons', function () {
        return view('pages.basic-ui.buttons');
    });
    Route::get('badges', function () {
        return view('pages.basic-ui.badges');
    });
    Route::get('breadcrumbs', function () {
        return view('pages.basic-ui.breadcrumbs');
    });
    Route::get('dropdowns', function () {
        return view('pages.basic-ui.dropdowns');
    });
    Route::get('modals', function () {
        return view('pages.basic-ui.modals');
    });
    Route::get('progress-bar', function () {
        return view('pages.basic-ui.progress-bar');
    });
    Route::get('pagination', function () {
        return view('pages.basic-ui.pagination');
    });
    Route::get('tabs', function () {
        return view('pages.basic-ui./');
    });
    Route::get('typography', function () {
        return view('pages.basic-ui.typography');
    });
    Route::get('tooltips', function () {
        return view('pages.basic-ui.tooltips');
    });
});

Route::group(['prefix' => 'advanced-ui'], function () {
    Route::get('dragula', function () {
        return view('pages.advanced-ui.dragula');
    });
    Route::get('clipboard', function () {
        return view('pages.advanced-ui.clipboard');
    });
    Route::get('context-menu', function () {
        return view('pages.advanced-ui.context-menu');
    });
    Route::get('popups', function () {
        return view('pages.advanced-ui.popups');
    });
    Route::get('sliders', function () {
        return view('pages.advanced-ui.sliders');
    });
    Route::get('carousel', function () {
        return view('pages.advanced-ui.carousel');
    });
    Route::get('loaders', function () {
        return view('pages.advanced-ui.loaders');
    });
    Route::get('tree-view', function () {
        return view('pages.advanced-ui.tree-view');
    });
});

Route::group(['prefix' => 'forms'], function () {
    Route::get('basic-elements', function () {
        return view('pages.forms.basic-elements');
    });
    Route::get('advanced-elements', function () {
        return view('pages.forms.advanced-elements');
    });
    Route::get('dropify', function () {
        return view('pages.forms.dropify');
    });
    Route::get('form-validation', function () {
        return view('pages.forms.form-validation');
    });
    Route::get('step-wizard', function () {
        return view('pages.forms.step-wizard');
    });
    Route::get('wizard', function () {
        return view('pages.forms.wizard');
    });
});

Route::group(['prefix' => 'editors'], function () {
    Route::get('text-editor', function () {
        return view('pages.editors.text-editor');
    });
    Route::get('code-editor', function () {
        return view('pages.editors.code-editor');
    });
});

Route::group(['prefix' => 'charts'], function () {
    Route::get('chartjs', function () {
        return view('pages.charts.chartjs');
    });
    Route::get('morris', function () {
        return view('pages.charts.morris');
    });
    Route::get('flot', function () {
        return view('pages.charts.flot');
    });
    Route::get('google-charts', function () {
        return view('pages.charts.google-charts');
    });
    Route::get('sparklinejs', function () {
        return view('pages.charts.sparklinejs');
    });
    Route::get('c3-charts', function () {
        return view('pages.charts.c3-charts');
    });
    Route::get('chartist', function () {
        return view('pages.charts.chartist');
    });
    Route::get('justgage', function () {
        return view('pages.charts.justgage');
    });
});

Route::group(['prefix' => 'tables'], function () {
    Route::get('basic-table', function () {
        return view('pages.tables.basic-table');
    });
    Route::get('data-table', function () {
        return view('pages.tables.data-table');
    });
    Route::get('js-grid', function () {
        return view('pages.tables.js-grid');
    });
    Route::get('sortable-table', function () {
        return view('pages.tables.sortable-table');
    });
});

Route::get('notifications', function () {
    return view('pages.notifications.index');
});

Route::group(['prefix' => 'icons'], function () {
    Route::get('material', function () {
        return view('pages.icons.material');
    });
    Route::get('flag-icons', function () {
        return view('pages.icons.flag-icons');
    });
    Route::get('font-awesome', function () {
        return view('pages.icons.font-awesome');
    });
    Route::get('simple-line-icons', function () {
        return view('pages.icons.simple-line-icons');
    });
    Route::get('themify', function () {
        return view('pages.icons.themify');
    });
});

Route::group(['prefix' => 'maps'], function () {
    Route::get('vector-map', function () {
        return view('pages.maps.vector-map');
    });
    Route::get('mapael', function () {
        return view('pages.maps.mapael');
    });
    Route::get('google-maps', function () {
        return view('pages.maps.google-maps');
    });
});

Route::group(['prefix' => 'user-pages'], function () {
    Route::get('login', function () {
        return view('pages.user-pages.login');
    });
    Route::get('login-2', function () {
        return view('pages.user-pages.login-2');
    });
    Route::get('multi-step-login', function () {
        return view('pages.user-pages.multi-step-login');
    });
    Route::get('register', function () {
        return view('pages.user-pages.register');
    });
    Route::get('register-2', function () {
        return view('pages.user-pages.register-2');
    });
    Route::get('lock-screen', function () {
        return view('pages.user-pages.lock-screen');
    });
});

Route::group(['prefix' => 'error-pages'], function () {
    Route::get('error-404', function () {
        return view('pages.error-pages.error-404');
    });
    Route::get('error-500', function () {
        return view('pages.error-pages.error-500');
    });
});

Route::group(['prefix' => 'general-pages'], function () {
    Route::get('blank-page', function () {
        return view('pages.general-pages.blank-page');
    });
    Route::get('landing-page', function () {
        return view('pages.general-pages.landing-page');
    });
    Route::get('profile', function () {
        return view('pages.general-pages.profile');
    });
    Route::get('email-templates', function () {
        return view('pages.general-pages.email-templates');
    });
    Route::get('faq', function () {
        return view('pages.general-pages.faq');
    });
    Route::get('faq-2', function () {
        return view('pages.general-pages.faq-2');
    });
    Route::get('news-grid', function () {
        return view('pages.general-pages.news-grid');
    });
    Route::get('timeline', function () {
        return view('pages.general-pages.timeline');
    });
    Route::get('search-results', function () {
        return view('pages.general-pages.search-results');
    });
    Route::get('portfolio', function () {
        return view('pages.general-pages.portfolio');
    });
    Route::get('user-listing', function () {
        return view('pages.general-pages.user-listing');
    });
});

Route::group(['prefix' => 'ecommerce'], function () {
    Route::get('invoice', function () {
        return view('pages.ecommerce.invoice');
    });
    Route::get('invoice-2', function () {
        return view('pages.ecommerce.invoice-2');
    });
    Route::get('pricing', function () {
        return view('pages.ecommerce.pricing');
    });
    Route::get('product-catalogue', function () {
        return view('pages.ecommerce.product-catalogue');
    });
    Route::get('project-list', function () {
        return view('pages.ecommerce.project-list');
    });
    Route::get('orders', function () {
        return view('pages.ecommerce.orders');
    });
});

// For Clear cache
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

// 404 for undefined routes
// Route::any('/{page?}',function(){
//     return View::make('pages.error-pages.error-404');
// })->where('page','.*');
