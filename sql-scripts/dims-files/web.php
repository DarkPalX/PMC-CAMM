<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\RedirectResponse;
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
// Register
Route::view('/register', 'admin.register.register');
Route::post('/register-success', 'RegisterController@register');

// Admin
Route::group(['namespace' => 'Admin'], function () { // app/Http/Controllers/Admin controllers
    Route::get('/', 'LoginController@index')->name('admin.login'); // login
    Route::post('login', 'LoginController@login')->name('admin.login.submit'); // login submit

    Route::get('adminlogin/admin', 'LoginController@adminLogin')->name('admin.login.adminLogin'); // login
    Route::post('adminsubmit/admin', 'LoginController@adminSubmit')->name('admin.login.adminsubmit'); // login submit
    //Route::get('/change-password', function() {
    // Route::get('/change_password', function() 
    // {
    //     $id = \Auth::user()->id;
    //     return view('admin.register.change_password');
    // });

    //Route::patch('/change-password', 'LoginController@updatePassword');

    Route::get('change_password', 'LoginController@change_password')->name('admin.change_password'); // change_password
    Route::post('updatePassword', 'LoginController@updatePassword')->name('admin.updatePassword'); // change password submit        

    // Admin authentication required routes
    Route::group(['middleware' => ['auth']], function () {


        Route::get('logout', 'LoginController@logout')->name('admin.logout'); // logout
        Route::get('dashboard', 'DashboardController@index')->name('admin.dashboard.index'); // dashboard

        // Driver routes
        Route::group(['prefix' => 'drivers'], function () {
            Route::post('/search-hris-employee', 'SearchController@search_hris_employee')->name('search.hris.employee');
            Route::post('information', 'DriverController@info')->name('admin.drivers.info');
            Route::get('licenses/{status}', 'DriverController@licenses')->name('admin.drivers.licenses');
            Route::get('listings/{status}', 'DriverController@listings')->name('admin.drivers.listings');

            Route::get('{id}/violations', 'DriverController@violations')->name('admin.drivers.violations');
            Route::post('{id}/violations/submit', 'DriverController@addViolation')->name('admin.drivers.violations.submit');
            Route::post('violations/edit', 'DriverController@getViolation')->name('admin.drivers.violations.edit');
            Route::put('{id}/violations/update', 'DriverController@updateViolation')->name('admin.drivers.violations.update');

            Route::put('{driverId}/update/{id}/license', 'DriverController@updateLicense')->name('admin.drivers.update.license');
            Route::put('update/{id}/photo', 'DriverController@updateDriverPhoto')->name('admin.drivers.update.photo');



        });

        // Driver resource
        Route::resource('drivers', 'DriverController', [
            'names' => [
                'index' => 'admin.drivers.index',
                'create' => 'admin.drivers.create',
                'store' => 'admin.drivers.store',
                'show' => 'admin.drivers.show',
                'edit' => 'admin.drivers.edit',
                'update' => 'admin.drivers.update',
                'destroy' => 'admin.drivers.destroy'
            ]
        ]);

        Route::get('deactivate/{id}', 'DriverController@deactivate')->name('admin.drivers.deactivate');
        Route::get('activate/{id}', 'DriverController@activate')->name('admin.drivers.activate');

        // Company routes
        Route::group(['prefix' => 'companies'], function () {
            Route::get('/', 'ContractorCompanyController@index')->name('admin.companies.index');
            Route::post('store', 'ContractorCompanyController@store')->name('admin.companies.store');
            Route::post('edit', 'ContractorCompanyController@edit')->name('admin.companies.edit');
            Route::put('update', 'ContractorCompanyController@update')->name('admin.companies.update');
            Route::put('{id}/status', 'ContractorCompanyController@changeStatus')->name('admin.companies.status');
        });

        // Vehicle routes
        Route::group(['prefix' => 'vehicles'], function () {
            Route::get('/', 'VehicleController@index')->name('admin.vehicles.index');
            Route::post('store', 'VehicleController@store')->name('admin.vehicles.store');
            Route::post('edit', 'VehicleController@edit')->name('admin.vehicles.edit');
            Route::put('update', 'VehicleController@update')->name('admin.vehicles.update');
            Route::put('{id}/status', 'VehicleController@changeStatus')->name('admin.vehicles.status');
        });

        // Violation routes
        Route::group(['prefix' => 'violations'], function () {
            Route::get('/', 'ViolationController@index')->name('admin.violations.index');
            Route::post('store', 'ViolationController@store')->name('admin.violations.store');
            Route::post('edit', 'ViolationController@edit')->name('admin.violations.edit');
            Route::put('update', 'ViolationController@update')->name('admin.violations.update');
            Route::put('{id}/status', 'ViolationController@changeStatus')->name('admin.violations.status');
            Route::get('listings', 'ViolationController@listings')->name('admin.violations.listings');
            Route::get('traffic-violation-report', 'ViolationController@trafficViolationReport')->name('admin.violations.traffic-violation-report');
            Route::get('{id}/report', 'ViolationController@reports')->name('admin.violations.reports');
        });

        // User routes
        Route::group(['prefix' => 'users'], function () {
            Route::get('/', 'UserController@index')->name('admin.users.index');
            Route::post('store', 'UserController@store')->name('admin.users.store');
            Route::post('edit', 'UserController@edit')->name('admin.users.edit');
            Route::put('update', 'UserController@update')->name('admin.users.update');
            Route::put('{id}/lock', 'UserController@lock')->name('admin.users.lock');
            Route::put('{id}/status', 'UserController@changeStatus')->name('admin.users.status');
            Route::delete('{id}/destroy', 'UserController@destroy')->name('admin.users.destroy');
            Route::get('logs', 'UserController@logs')->name('admin.users.logs');
            Route::post('logs/filter', 'UserController@logs')->name('admin.users.logs.filter');
        });

        // Chart routes
        Route::group(['prefix' => 'charts'], function () {
            // Newly registered licenses
            Route::get('licenses/registered/new/panel', 'ChartController@newlyRegisteredLicenses')->name('admin.charts.newly_registered_licenses.panel');
            Route::get('licenses/registered/new/page', 'ChartController@newlyRegisteredLicensesPage')->name('admin.charts.newly_registered_licenses.page');
            Route::get('licenses/registered/new/page/filter', 'ChartController@newlyRegisteredLicensesPage')->name('admin.charts.newly_registered_licenses.page.filter');

            // Chart database per company
            Route::get('company-chart/panel', 'ChartController@chartDatabasePerCompany')->name('admin.charts.per_company.panel');
            Route::get('company-chart/page', 'ChartController@chartDatabasePerCompanyPage')->name('admin.charts.per_company.page');
            Route::get('company-chart/page/filter', 'ChartController@chartDatabasePerCompanyPage')->name('admin.charts.per_company.page.filter');

            // Expiry licenses per month
            Route::get('licenses/expiry/panel', 'ChartController@expiryLicensesPerMonth')->name('admin.charts.expiry_licenses.panel');
            Route::get('licenses/expiry/page', 'ChartController@expiryLicensesPerMonthPage')->name('admin.charts.expiry_licenses.page');
            Route::get('licenses/expiry/page/filter', 'ChartController@expiryLicensesPerMonthPage')->name('admin.charts.expiry_licenses.page.filter');

            // Offense category
            Route::get('violations/category/panel', 'ChartController@offenseCategory')->name('admin.charts.offense_category.panel');
            Route::get('violations/category/page', 'ChartController@offenseCategoryPage')->name('admin.charts.offense_category.page');
            Route::get('violations/category/page/filter', 'ChartController@offenseCategoryPage')->name('admin.charts.offense_category.page.filter');

            // Monthly violations
            Route::get('violations/monthly/panel', 'ChartController@monthlyViolations')->name('admin.charts.monthly_violations.panel');
            Route::get('violations/monthly/page', 'ChartController@monthlyViolationsPage')->name('admin.charts.monthly_violations.page');
            Route::get('violations/monthly/page/filter', 'ChartController@monthlyViolationsPage')->name('admin.charts.monthly_violations.page.filter');

            // Violations committed
            Route::get('violations/committed/panel', 'ChartController@violationsCommitted')->name('admin.charts.violations_committed.panel');
            Route::get('violations/committed/page', 'ChartController@violationsCommittedPage')->name('admin.charts.violations_committed.page');
            Route::get('violations/committed/page/filter', 'ChartController@violationsCommittedPage')->name('admin.charts.violations_committed.page.filter');
        });

        // Role routes
        Route::group(['prefix' => 'roles'], function () {
            Route::get('/', 'RoleController@index')->name('admin.roles.index');
            Route::post('store', 'RoleController@store')->name('admin.roles.store');
            Route::post('edit', 'RoleController@edit')->name('admin.roles.edit');
            Route::put('update', 'RoleController@update')->name('admin.roles.update');
            Route::put('{id}/lock', 'RoleController@lock')->name('admin.roles.lock');
            Route::put('{id}/status', 'RoleController@changeStatus')->name('admin.roles.status');
            Route::delete('{id}/destroy', 'RoleController@destroy')->name('admin.roles.destroy');
        });

        // Permission routes
        Route::group(['prefix' => 'permissions'], function () {
            Route::get('/', 'PermissionController@index')->name('admin.permissions.index');
            Route::post('store', 'PermissionController@store')->name('admin.permissions.store');
            Route::post('edit', 'PermissionController@edit')->name('admin.permissions.edit');
            Route::put('update', 'PermissionController@update')->name('admin.permissions.update');
            Route::put('{id}/lock', 'PermissionController@lock')->name('admin.permissions.lock');
            Route::put('{id}/status', 'PermissionController@changeStatus')->name('admin.permissions.status');
            Route::delete('{id}/destroy', 'PermissionController@destroy')->name('admin.permissions.destroy');
        });

        //User Access right routes
        Route::group(['prefix' => 'useraccessrights'], function () {
            Route::get('index', 'UserRightController@index')->name('admin.useraccessrights.index');
            Route::post('store', 'UserRightController@store')->name('admin.useraccessrights.store');
            Route::get('store', 'UserRightController@store')->name('admin.useraccessrights.store');
        });

        //Role Access right routes
        Route::group(['prefix' => 'roleaccessrights'], function () {
            Route::get('index', 'RoleRightController@index')->name('admin.roleaccessrights.index');
            Route::post('store', 'RoleRightController@store')->name('admin.roleaccessrights.store');
            Route::get('store', 'RoleRightController@store')->name('admin.roleaccessrights.store');
        });

        //Audit routes
        Route::group(['prefix' => 'audits'], function () {
            Route::get('index', 'AuditController@index')->name('admin.audits.index');
        });

        // Report
        Route::group(['prefix' => 'reports'], function () {
            Route::get('licenses/{status}', 'ReportController@licenses')->name('admin.reports.licenses');
            Route::get('listings/{status}', 'ReportController@listings')->name('admin.reports.listings');
            Route::get('violations', 'ReportController@violations')->name('admin.reports.violations');
            Route::get('traffic-violation-report', 'ReportController@trafficViolationReport')->name('admin.reports.traffic-violation-report');
            Route::get('audit-logs', 'ReportController@auditLogs')->name('admin.reports.audit-logs');
            // Route::post('audit-logs', 'ReportController@auditLogs')->name('admin.reports.audit-logs');
            Route::get('error-logs', 'ReportController@errorLogs')->name('admin.reports.error-logs');
        });

        // Application routes
        Route::group(['prefix' => 'application/admin'], function () {
            Route::get('/', 'ApplicationController@index')->name('admin.application.index');
            Route::post('store', 'ApplicationController@store')->name('admin.application.store');
            Route::post('edit', 'ApplicationController@edit')->name('admin.application.edit');
            Route::put('update', 'ApplicationController@update')->name('admin.application.update');
            Route::delete('{id}/destroy', 'ApplicationController@destroy')->name('admin.application.destroy');
            
            Route::get('{id}/destroy', 'ApplicationController@destroy')->name('admin.application.destroy');

            Route::get('systemDown', 'ApplicationController@systemDown')->name('admin.application.systemDown');
            Route::get('systemUp', 'ApplicationController@systemUp')->name('admin.application.systemUp');
            
            Route::get('create_indexing', 'ApplicationController@create_indexing')->name('admin.application.create_indexing');
        });
    });
   
});
