<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SearchController;

Route::middleware(['auth'])->get('/global-search', [SearchController::class, 'globalSearch'])->name('global.search');

// ------------------ Admin Controllers ------------------
use App\Http\Controllers\Admin\{
    DashboardController as AdminDashboardController,
    EmployeeController as AdminEmployeeController,
    CustomerController as AdminCustomerController,
    ServiceController as AdminServiceController,
    TaskController as AdminTaskController,
    ScheduleController as AdminScheduleController,
    ReportController as AdminReportController,
    RoleController as AdminRoleController,
    RoleController,
    SettingController as AdminSettingsController,
    AdminAppointmentController,
    UserController
};

// ------------------ Manager Controllers ------------------
use App\Http\Controllers\Manager\{
    DashboardController as ManagerDashboardController,
    TeamController,
    TaskController as ManagerTaskController,
    ReportController as ManagerReportController,
    ManagerControlAppointmentController,
    ManagerCustomerController
};
// ------------------ Employee Controllers ------------------
use App\Http\Controllers\Employee\{
    DashboardController as EmployeeDashboardController,
    TaskController as EmployeeTaskController,
    ReportController as EmployeeReportController,
    AppointmentController as EmployeeAppointmentController,
    EmployeeBookingController,
    EmployeePerformanceController,
    EmployeeSupportController
};

// ------------------ Customer Controllers ------------------
use App\Http\Controllers\Customer\{
    DashboardController as CustomerDashboardController,
    BookingController as CustomerBookingController,
    AppointmentController as CustomerAppointmentController,
};

// ------------------ Other Controllers ------------------
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Admin\AdminSupportController;
use App\Http\Controllers\Manager\ManagerSupportController;
use App\Http\Controllers\Customer\CustomerSupportController;
use App\Http\Controllers\Manager\ManagerInvoiceController;
// ------------------ Public Website Routes ------------------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/about-us', 'web.about-us')->name('web.about-us');
Route::view('/support', 'web.support')->name('web.support');
Route::view('/privacy-policy', 'web.privacy-policy')->name('web.privacy-policy');
Route::view('/terms-of-use', 'web.terms-of-use')->name('web.terms-of-use');
Route::view('/faq', 'web.faq')->name('web.faq');
Route::view('/contact', 'web.contact')->name('web.contact');
Route::view('/ourselves', 'web.ourselves')->name('web.ourselves');



//
Route::get('/appointment1', function () {
    return 'This is appointment 1';
})->name('web.appointment');

Route::get('/appointment2', function () {
    return 'This is appointment 2';
})->name('web.appointment');


Route::get('auth/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

// ------------------ Auth Routes ------------------
Route::get('/auth/register', [RegisterUserController::class, 'showRegistrationForm'])->name('auth.register');
Route::post('/auth/register', [RegisterUserController::class, 'register'])->name('auth.register.submit');
Route::get('/auth/login', [LoginController::class, 'showLoginForm'])->name('auth.login');
Route::post('/auth/login', [LoginController::class, 'login'])->name('auth.login.submit');
Route::post('/auth/logout', [LoginController::class, 'logout'])->name('auth.logout');

Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

// ------------------ Profile Routes ------------------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/theme', [ProfileController::class, 'updateTheme'])->name('profile.theme');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// Route to handle profile photo upload or update
Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])
     ->name('profile.photo');



use App\Http\Controllers\Web\WebServiceController;

Route::get('/services', [WebServiceController::class, 'index'])
    ->name('web.services');




    use App\Http\Controllers\SubscribeController;
Route::post('subscribers/store', [SubscribeController::class, 'store'])->name('subscribe.store');
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/subscribers', [SubscribeController::class, 'index'])->name('subscribers.index');
    Route::post('/subscribers/send-update', [SubscribeController::class, 'sendCompanyUpdate'])->name('subscribers.sendUpdate');
    Route::get('/subscribers/export/pdf', [SubscribeController::class, 'exportPDF'])->name('subscribers.export.pdf');
    Route::get('/subscribers/export/excel', [SubscribeController::class, 'exportExcel'])->name('subscribers.export.excel');
    Route::post('/subscribers/bulk-destroy', [SubscribeController::class, 'bulkDestroy'])->name('subscribers.bulk-destroy');
    Route::get('/subscribers/{subscriber}', [SubscribeController::class, 'show'])->name('subscribers.show');
    Route::delete('/subscribers/{subscriber}', [SubscribeController::class, 'destroy'])->name('subscribers.destroy');
});



use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

Route::middleware('guest')->group(function() {
    Route::get('forgot-password', [ForgotPasswordController::class, 'show'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'send'])->name('password.email');
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'show'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'update'])->name('password.update');
});

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');


use App\Http\Controllers\Manager\DashboardController;

// Dashboard route
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// ------------------ Admin Routes ------------------
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // CRUD Pages
    Route::get('/employees', [AdminEmployeeController::class, 'index'])->name('employees');
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers');
    Route::get('/services', [AdminServiceController::class, 'index'])->name('services');
    Route::get('/tasks', [AdminTaskController::class, 'index'])->name('tasks');
    Route::get('/schedules', [AdminScheduleController::class, 'index'])->name('schedules');
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports');
    Route::get('/roles-permissions', [RoleController::class, 'index'])->name('roles_permissions');
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings');

    // Send Message to Employee
    Route::post('/employees/{id}/message', [AdminEmployeeController::class, 'sendMessage'])->name('employees.sendMessage');

    // Update permissions
   Route::post('/settings/update-permissions', [AdminSettingsController::class, 'updatePermissions'])
    ->name('settings.update-permissions');


    // Users CRUD + Reports
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Users Reports
    Route::get('/users/report', [UserController::class, 'report'])->name('users.report');
    Route::get('/users/report/pdf', [UserController::class, 'reportPdf'])->name('users.report.pdf');
    Route::get('/users/report/excel', [UserController::class, 'reportExcel'])->name('users.report.excel');
    Route::post('/users/report/email', [UserController::class, 'sendReportEmail'])->name('users.report.email');

    // Extra Appointment Routes (Before Resource)
    Route::get('/appointments/{id}/json', [AdminAppointmentController::class, 'showJson'])->name('appointments.json');
    Route::get('/appointments/generate-report', [AdminAppointmentController::class, 'generateReport'])->name('appointments.generateReport');
    Route::get('/appointments/export-excel', [AdminAppointmentController::class, 'exportExcel'])->name('appointments.exportExcel');

    // Resource CRUD
    Route::resources([
        'employees' => AdminEmployeeController::class,
        'customers' => AdminCustomerController::class,
        'services' => AdminServiceController::class,
        'tasks' => AdminTaskController::class,
        'schedules' => AdminScheduleController::class,
        'appointments' => AdminAppointmentController::class,
    ]);

    // Extra Admin appointment actions
    Route::post('appointments/generate-report', [AdminAppointmentController::class, 'generateReport'])->name('appointments.generateReport');
    Route::post('appointments/assign-employee/{appointment}', [AdminAppointmentController::class, 'assignEmployee'])->name('appointments.assignEmployee');
});

// Roles & Permissions routes moved to main admin group or unified here
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::patch('roles/{id}', [RoleController::class, 'update'])->name('admin.roles.update');
    
    // Management routes
    Route::post('roles/store', [RoleController::class, 'storeRole'])->name('admin.roles.storeRole');
    Route::delete('roles/destroy/{id}', [RoleController::class, 'destroyRole'])->name('admin.roles.destroyRole');
    Route::post('permissions/store', [RoleController::class, 'storePermission'])->name('admin.permissions.storePermission');
    Route::delete('permissions/destroy/{id}', [RoleController::class, 'destroyPermission'])->name('admin.permissions.destroyPermission');
    Route::post('roles/toggle-permission', [RoleController::class, 'togglePermission'])->name('admin.roles.togglePermission');
});

// ------------------ Manager Routes ------------------
Route::prefix('manager')->name('manager.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');
    
    // Team Orchestration
    Route::resource('team', TeamController::class);
    
    // Client Intelligence
    Route::resource('customer', ManagerCustomerController::class);
    
    // Tactical Operations
    Route::resource('tasks', ManagerTaskController::class);
    
    // Historical Appointments (Legacy reference, keeping for compatibility if needed)
    Route::get('/appointments-legacy', [ManagerControlAppointmentController::class, 'index'])->name('appointments.legacy');
});

// ------------------ Employee Routes ------------------
Route::prefix('employee')->name('employee.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');
    Route::get('/tasks', [EmployeeTaskController::class, 'index'])->name('tasks');
    Route::post('/tasks/{id}/status', [EmployeeTaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    Route::get('/tasks/{id}/details', [EmployeeTaskController::class, 'getDetails'])->name('tasks.details');
    
    Route::get('/reports', [EmployeeReportController::class, 'index'])->name('reports');
    Route::post('/reports', [EmployeeReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{id}/details', [EmployeeReportController::class, 'getDetails'])->name('reports.details');
    Route::resource('reports', EmployeeReportController::class)->except(['index', 'store']);
    
    Route::get('/appointments', [EmployeeAppointmentController::class, 'index'])->name('appointments.index');
    Route::post('/appointments/help', [EmployeeAppointmentController::class, 'help'])->name('appointments.help');
    Route::get('/appointments/{id}/details', [EmployeeAppointmentController::class, 'getDetails'])->name('appointments.details');
    Route::post('/appointments/{id}/status', [EmployeeAppointmentController::class, 'ajaxUpdateStatus'])->name('appointments.status.update');

    Route::get('/support', [EmployeeSupportController::class, 'index'])->name('support.index');
    Route::get('/support/details/{id}', [EmployeeSupportController::class, 'details'])->name('support.details');
    Route::post('/support/reply', [EmployeeSupportController::class, 'reply'])->name('support.reply');
    Route::get('/support/history/{id}', [EmployeeSupportController::class, 'history'])->name('support.history');

    Route::get('/performance', [EmployeePerformanceController::class, 'index'])->name('performance.index');

    Route::resource('tasks', EmployeeTaskController::class)->except(['index']);
    Route::resource('bookings', EmployeeBookingController::class);
    Route::post('/bookings/{id}/status', [EmployeeBookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    Route::get('/bookings/{id}/details', [EmployeeBookingController::class, 'getDetails'])->name('bookings.details');
});


// ------------------ Customer Routes ------------------
Route::prefix('customer')->name('customer.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/bookings', [CustomerBookingController::class, 'index'])->name('bookings');
    Route::get('/support', [CustomerSupportController::class, 'index'])->name('support');

    Route::get('/appointments', [CustomerAppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [CustomerAppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [CustomerAppointmentController::class, 'store'])->name('appointments.store');
    Route::put('/appointments/{appointment}', [CustomerAppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('/appointments/{appointment}', [CustomerAppointmentController::class, 'destroy'])->name('appointments.destroy');

    Route::resource('bookings', CustomerBookingController::class)->except(['index']);
    Route::resource('support', CustomerSupportController::class)->except(['index']);
    Route::get('/appointments/{appointment}', [CustomerAppointmentController::class, 'show'])
    ->name('appointments.show');

});

// ------------------ Password Change ------------------
Route::get('/password/change', [PasswordController::class, 'showChangeForm'])->name('password.change');
Route::post('/password/change', [PasswordController::class, 'update'])->name('password.update');


use App\Http\Controllers\Admin\SettingController;

Route::prefix('admin/settings')->middleware(['auth'])->group(function () {
    // Existing settings page
    Route::get('/', [SettingController::class, 'index'])->name('admin.settings');

    // Add this POST route for updating permissions
    Route::post('update-permissions', [SettingController::class, 'updatePermissions'])
        ->name('admin.settings.update-permissions');
});
Route::post('/admin/settings/update-permissions', [App\Http\Controllers\Admin\SettingController::class, 'updatePermissions'])
    ->name('admin.settings.update-permissions')
    ->middleware(['auth', 'web']);
Route::get('/admin/settings', [SettingController::class, 'index'])->name('admin.settings');


use App\Http\Controllers\Admin\AppointmentController;

// Route moved to main admin group
Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Other appointment routes if any
    Route::post('/appointments/update-status', [AdminAppointmentController::class, 'updateStatus'])
        ->name('admin.appointments.updateStatus');
    Route::post('/appointments/{id}/priority', [AdminAppointmentController::class, 'updatePriority']);
    Route::post('/appointments/{id}/assign', [AdminAppointmentController::class, 'assignEmployeeAjax']);
});


// Manager
Route::prefix('manager')->name('manager.')->middleware(['auth'])->group(function () {
    
    // Appointments routes
    Route::get('/appointments', [ManagerControlAppointmentController::class, 'index'])
        ->name('appointments');

    Route::get('/appointments/{appointment}', [ManagerControlAppointmentController::class, 'show'])
        ->name('appointments.show');

    Route::patch('/appointments/{appointment}/complete', [ManagerControlAppointmentController::class, 'complete'])
        ->name('appointments.complete');

    Route::patch('/appointments/{appointment}/assign', [ManagerControlAppointmentController::class, 'assignEmployee'])
        ->name('appointments.assign');

    Route::delete('/appointments/{appointment}', [ManagerControlAppointmentController::class, 'destroy'])
        ->name('appointments.destroy');

    Route::get('/appointments/export', [ManagerControlAppointmentController::class, 'export'])
        ->name('appointments.export');

    Route::patch('/appointments/{appointment}/status', [ManagerControlAppointmentController::class, 'updateStatus'])
        ->name('appointments.updateStatus');
});
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/appointments', [\App\Http\Controllers\Customer\AppointmentController::class, 'index'])
        ->name('appointments.index');
});


Route::prefix('employee')->name('employee.')->middleware(['auth'])->group(function () {
    Route::resource('appointments', App\Http\Controllers\Employee\AppointmentController::class)
        ->only(['index']); // you can add create, store, show, etc. later
});


// ------------------ Web Auth ------------------
// Customer routes
Route::prefix('customer')->name('customer.')->middleware(['auth'])->group(function () {
    Route::resource('bookings', \App\Http\Controllers\Customer\CustomerBookingController::class);
});

// Employee routes
Route::prefix('employee')->name('employee.')->middleware(['auth'])->group(function () {
    Route::resource('bookings', \App\Http\Controllers\Employee\EmployeeBookingController::class);
});

// Manager routes
Route::prefix('manager')->name('manager.')->middleware(['auth'])->group(function () {
    Route::resource('bookings', \App\Http\Controllers\Manager\ManagerBookingController::class);
});

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::resource('bookings', \App\Http\Controllers\Admin\AdminBookingController::class);
});

//Team
Route::get('/manager/team', [TeamController::class, 'index'])->name('manager.team.index');



//use App\Http\Controllers\Admin\AdminServiceController;
use App\Http\Controllers\Manager\ManagerServiceController;
use App\Http\Controllers\Admin\ServiceController;
// Manager routes
Route::prefix('manager')->name('manager.')->middleware(['auth'])->group(function () {
    Route::resource('services', ManagerServiceController::class);
});

Route::prefix('manager')->name('manager.')->middleware(['auth'])->group(function () {
    
    // Resource routes for services
    Route::resource('services', ManagerServiceController::class);

    // Publish service route
    Route::patch('services/{service}/publish', [ManagerServiceController::class, 'publish'])
        ->name('services.publish');
});
Route::patch('manager/services/{service}/publish', [ManagerServiceController::class, 'publish'])->name('manager.services.publish');


use App\Http\Controllers\Manager\ManagerBookingController;
// Route for manager to generate report
Route::get('/manager/bookings/generate-report', [ManagerBookingController::class, 'generateReport'])
    ->name('manager.bookings.generateReport')
    ->middleware('auth');

// Booking status update
Route::post('/manager/bookings/{booking}/update-status/{status}', [ManagerBookingController::class, 'updateStatus'])
    ->name('manager.bookings.updateStatus')
    ->middleware(['auth']); // or add 'role:manager' if you have role middleware

// Reschedule page (optional, already in your buttons)
Route::get('/manager/bookings/{booking}/reschedule', [ManagerBookingController::class, 'reschedule'])
    ->name('manager.bookings.reschedule')
    ->middleware(['auth']);
// In routes/web.php
Route::get('manager/bookings/{booking}/reschedule', [ManagerBookingController::class, 'reschedule'])->name('manager.bookings.reschedule');
Route::patch('manager/bookings/{booking}/reschedule', [ManagerBookingController::class, 'updateReschedule'])->name('manager.bookings.updateReschedule');




// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::resource('services',ServiceController::class);
});
Route::patch('admin/services/{service}/publish', [ServiceController::class, 'publish'])->name('admin.services.publish');
use App\Http\Controllers\Admin\AdminBookingController;
// Route for manager to generate report
Route::get('/admin/bookings/generate-report', [AdminBookingController::class, 'generateReport'])
    ->name('admin.bookings.generateReport');

// Booking status update
Route::post('/admin/bookings/{booking}/update-status/{status}', [AdminBookingController::class, 'updateStatus'])
    ->name('admin.bookings.updateStatus')
    ->middleware(['auth']); // or add 'role:Admin' if you have role middleware

// Reschedule page (optional, already in your buttons)
Route::get('/admin/bookings/{booking}/reschedule', [AdminBookingController::class, 'reschedule'])
    ->name('admin.bookings.reschedule')
    ->middleware(['auth']);
// In routes/web.php
Route::get('admin/bookings/{booking}/reschedule', [AdminBookingController::class, 'reschedule'])->name('admin.bookings.reschedule');
Route::patch('admin/bookings/{booking}/reschedule', [AdminBookingController::class, 'updateReschedule'])->name('admin.bookings.updateReschedule');

//
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Manager\ManagerProductController;
use App\Http\Controllers\Manager\ManagerStockInController;
use App\Http\Controllers\Manager\ManagerStockOutController;
use App\Http\Controllers\Manager\AdminStockInController;
// routes/web.php
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    // Products
    Route::get('products', [\App\Http\Controllers\Admin\AdminProductController::class, 'index'])->name('product.index');
    Route::get('products/create', [\App\Http\Controllers\Admin\AdminProductController::class, 'create'])->name('product.create');
    Route::post('product', [\App\Http\Controllers\Admin\AdminProductController::class, 'store'])->name('product.store');
    Route::get('products/{id}', [\App\Http\Controllers\Admin\AdminProductController::class, 'show'])->name('product.show');
    Route::get('products/{id}/edit', [\App\Http\Controllers\Admin\AdminProductController::class, 'edit'])->name('product.edit');
    Route::put('products/{id}', [\App\Http\Controllers\Admin\AdminProductController::class, 'update'])->name('product.update');
    Route::delete('products/{id}', [\App\Http\Controllers\Admin\AdminProductController::class, 'destroy'])->name('product.destroy');

    Route::get('products/export/pdf', [AdminProductController::class, 'exportPdf'])->name('products.export.pdf');
    Route::get('products/export/excel', [AdminProductController::class, 'exportExcel'])->name('products.export.excel');

    // Stock In
    Route::get('stock_in', [\App\Http\Controllers\Admin\AdminStockInController::class, 'index'])->name('stock_in.index');
    Route::get('stock_in/create', [\App\Http\Controllers\Admin\AdminStockInController::class, 'create'])->name('stock_in.create');
    Route::post('stock_in', [\App\Http\Controllers\Admin\AdminStockInController::class, 'store'])->name('stock_in.store');
    Route::get('stock_in/{id}/edit', [\App\Http\Controllers\Admin\AdminStockInController::class, 'edit'])->name('stock_in.edit');
    Route::put('stock_in/{id}', [\App\Http\Controllers\Admin\AdminStockInController::class, 'update'])->name('stock_in.update');
    Route::delete('stock_in/{id}', [\App\Http\Controllers\Admin\AdminStockInController::class, 'destroy'])->name('stock_in.destroy');

    // Stock Out
    Route::get('stockout', [\App\Http\Controllers\Admin\AdminStockOutController::class, 'index'])->name('stockout.index');
    Route::get('admin/stock_out', [\App\Http\Controllers\Admin\AdminStockOutController::class, 'create'])->name('admin.stockout.create');
    Route::post('stock_out', [\App\Http\Controllers\Admin\AdminStockOutController::class, 'store'])->name('stock_out.store');
    Route::get('stockout/{id}/edit', [\App\Http\Controllers\Admin\AdminStockOutController::class, 'edit'])->name('stockout.edit');
    Route::put('stockout/{id}', [\App\Http\Controllers\Admin\AdminStockOutController::class, 'update'])->name('stockout.update');
    Route::delete('stockout/{id}', [\App\Http\Controllers\Admin\AdminStockOutController::class, 'destroy'])->name('stockout.destroy');

    //Report
    Route::get('/stock_in/pdf', [\App\Http\Controllers\Admin\AdminStockInController::class, 'generatePdf'])->name('stock_in.pdf');
    Route::get('/stock_in/excel', [\App\Http\Controllers\Admin\AdminStockInController::class, 'generateExcel'])->name('stock_in.excel');
});


Route::get('/admin/stock_in/add-supplier', [\App\Http\Controllers\Admin\AdminStockInController::class, 'addSupplier'])
    ->name('admin.stock_in.addSupplier');
Route::post('/admin/stock_in/store-supplier', [\App\Http\Controllers\Admin\AdminStockInController::class, 'storeSupplier'])
    ->name('admin.stock_in.storeSupplier');


// routes/web.php (or routes/admin.php if using a separate admin group)

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\AdminStockOutController;

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::resource('customer', CustomerController::class);
    Route::post('/customer/store', [CustomerController::class, 'store'])->name('customer.store');
    Route::get('customers-report', [CustomerController::class, 'report'])->name('customers.report');
});

Route::prefix('admin/stockout')->name('admin.stockout.')->middleware(['auth'])->group(function () {
    Route::get('/', [AdminStockOutController::class, 'index'])->name('index');
    Route::post('/store', [AdminStockOutController::class, 'store'])->name('store');
    Route::post('/store-customer', [AdminStockOutController::class, 'storeCustomer'])->name('storeCustomer');
    Route::get('/product-price/{id}', [AdminStockOutController::class, 'getProductPrice'])->name('product-price');
    Route::get('/{id}/json', [AdminStockOutController::class, 'getStockOutJson'])->name('json');
    Route::put('/{id}/update', [AdminStockOutController::class, 'update'])->name('update');
    Route::delete('/{id}/delete', [AdminStockOutController::class, 'destroy'])->name('delete');
    Route::get('/check-stock/{id}', [AdminStockOutController::class, 'checkStock'])->name('check-stock');
});


// StockOut Reports
Route::get('/admin/stockout/report/pdf', [App\Http\Controllers\Admin\AdminStockOutController::class, 'reportPdf'])->name('admin.stockout.report.pdf');
Route::get('/admin/stockout/report/excel', [App\Http\Controllers\Admin\AdminStockOutController::class, 'reportExcel'])->name('admin.stockout.report.excel');
Route::prefix('admin')->name('admin.')->group(function() {
    // Other stockout routes...
    Route::get('stockout/available', [AdminStockOutController::class, 'available'])->name('stockout.available');
});
// Example route

Route::get('/admin/stockout/stock-overview', [AdminStockOutController::class, 'stockOverview'])
     ->name('admin.stockout.stockOverview');

Route::get('/admin/stockout/daily-transactions', [AdminStockOutController::class, 'dailyTransactions'])
     ->name('admin.stockout.dailyTransactions');




// Manager StockOut Overview
Route::get('/manager/stockout/stock-overview', [ManagerStockOutController::class, 'stockOverview'])
     ->name('manager.stockout.stockOverview');








//
Route::prefix('manager')->name('manager.')->middleware(['auth'])->group(function () {

    // Products
    Route::get('products', [\App\Http\Controllers\Manager\ManagerProductController::class, 'index'])->name('product.index');
    Route::get('products/create', [\App\Http\Controllers\Manager\ManagerProductController::class, 'create'])->name('product.create');
    Route::post('product', [\App\Http\Controllers\Manager\ManagerProductController::class, 'store'])->name('product.store'); // <-- must match /products
    Route::get('products/{id}/edit', [\App\Http\Controllers\Manager\ManagerProductController::class, 'edit'])->name('product.edit');
    Route::put('products/{id}', [\App\Http\Controllers\Manager\ManagerProductController::class, 'update'])->name('product.update');
    Route::delete('products/{id}', [\App\Http\Controllers\Manager\ManagerProductController::class, 'destroy'])->name('product.destroy');

    // Product Exports
    Route::get('products/export/pdf', [\App\Http\Controllers\Manager\ManagerProductController::class, 'exportPdf'])->name('products.export.pdf');
    Route::get('products/export/excel', [\App\Http\Controllers\Manager\ManagerProductController::class, 'exportExcel'])->name('products.export.excel');

    // Stock In
    Route::get('stockin', [\App\Http\Controllers\Manager\ManagerStockInController::class, 'index'])->name('stockin.index');
    Route::get('stockin/create', [\App\Http\Controllers\Manager\ManagerStockInController::class, 'create'])->name('stockin.create');
    Route::post('stock_in', [\App\Http\Controllers\Manager\ManagerStockInController::class, 'store'])->name('stock_in.store');
    Route::get('stockin/{id}/edit', [\App\Http\Controllers\Manager\ManagerStockInController::class, 'edit'])->name('stockin.edit');
    Route::put('stockin/{id}', [\App\Http\Controllers\Manager\ManagerStockInController::class, 'update'])->name('stockin.update');
    Route::delete('stockin/{id}', [\App\Http\Controllers\Manager\ManagerStockInController::class, 'destroy'])->name('stockin.destroy');
    Route::get('stockin/export/pdf', [\App\Http\Controllers\Manager\ManagerStockInController::class, 'exportPdf'])->name('stockin.export.pdf');
    Route::get('stockin/export/excel', [\App\Http\Controllers\Manager\ManagerStockInController::class, 'exportExcel'])->name('stockin.export.excel');


    // Stock Out
    Route::get('stockout', [\App\Http\Controllers\Manager\ManagerStockOutController::class, 'index'])->name('stockout.index');
    Route::get('stockout/create', [\App\Http\Controllers\Manager\ManagerStockOutController::class, 'create'])->name('stockout.create');
    Route::post('stockout', [\App\Http\Controllers\Manager\ManagerStockOutController::class, 'store'])->name('stockout.store');
    Route::get('stockout/{id}/edit', [\App\Http\Controllers\Manager\ManagerStockOutController::class, 'edit'])->name('stockout.edit');
    Route::put('stockout/{id}', [\App\Http\Controllers\Manager\ManagerStockOutController::class, 'update'])->name('stockout.update');
    Route::delete('stockout/{id}', [\App\Http\Controllers\Manager\ManagerStockOutController::class, 'destroy'])->name('stockout.destroy');
    Route::get('stockout/export/pdf', [\App\Http\Controllers\Manager\ManagerStockOutController::class, 'exportPdf'])->name('stockout.export.pdf');
    Route::get('stockout/export/excel', [\App\Http\Controllers\Manager\ManagerStockOutController::class, 'exportExcel'])->name('stockout.export.excel');
    Route::get('stockout/available', [ManagerStockOutController::class, 'available'])->name('stockout.available');

    Route::prefix('stockout')->name('stockout.')->group(function () {
        Route::post('/store-customer', [ManagerStockOutController::class, 'storeCustomer'])->name('storeCustomer');
        Route::get('/product-price/{id}', [ManagerStockOutController::class, 'getProductPrice'])->name('product-price');
        Route::get('/{id}/json', [ManagerStockOutController::class, 'getStockOutJson'])->name('json');
        Route::delete('/{id}/delete', [ManagerStockOutController::class, 'destroy'])->name('delete');
        Route::get('/check-stock/{id}', [ManagerStockOutController::class, 'checkStock'])->name('check-stock');
    });

    // Orders
    Route::get('/orders/ledger', [\App\Http\Controllers\Manager\ManagerOrderController::class, 'index'])->name('orders.ledger');
    Route::get('/orders/export/pdf', [\App\Http\Controllers\Manager\ManagerOrderController::class, 'exportPdf'])->name('orders.export.pdf');
    Route::get('/orders/export/excel', [\App\Http\Controllers\Manager\ManagerOrderController::class, 'exportExcel'])->name('orders.export.excel');

    // Reports Hub
    Route::get('reports', [\App\Http\Controllers\Manager\ReportController::class, 'index'])->name('reports.index');

});

// Invoice
Route::get('/manager/invoice/create', [ManagerInvoiceController::class, 'create'])->name('manager.invoice.create');
Route::post('/manager/invoice/store', [ManagerInvoiceController::class, 'store'])->name('manager.invoice.store');
Route::get('/manager/invoice/{id}/print', [ManagerInvoiceController::class, 'print'])->name('manager.invoice.print');




Route::delete('admin/stock_in/{id}', [\App\Http\Controllers\Admin\AdminStockInController::class, 'destroy'])
    ->name('admin.stock_in.destroy');
Route::put('admin/stock_in/{id}', [\App\Http\Controllers\Admin\AdminStockInController::class, 'update'])
    ->name('admin.stock_in.update');

    //Manager
Route::delete('manager/stock_in/{id}', [\App\Http\Controllers\Manager\ManagerStockInController::class, 'destroy'])
    ->name('manager.stock_in.destroy');
Route::put('manager/stock_in/{id}', [\App\Http\Controllers\Manager\ManagerStockInController::class, 'update'])
    ->name('manager.stock_in.update');



use App\Http\Controllers\Admin\CategoryController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', CategoryController::class);
});
use App\Http\Controllers\Manager\ManagerCategoryController;

Route::prefix('manager')->name('manager.')->group(function () {
    Route::resource('categories', ManagerCategoryController::class);
});

//Supplier routes
use App\Http\Controllers\Admin\SupplierController;

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::post('/suppliers/store', [SupplierController::class, 'store'])->name('admin.suppliers.store');
});
use App\Http\Controllers\Manager\ManagerSupplierController;

Route::prefix('manager')->middleware(['auth'])->group(function () {
    Route::post('/suppliers/store', [ManagerSupplierController::class, 'store'])->name('manager.suppliers.store');
});



//Orders
use App\Http\Controllers\Customer\CustomerOrderController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Manager\ManagerOrderController;
// Customer routes
Route::prefix('customer')->group(function () {
    Route::get('orders', [CustomerOrderController::class, 'index'])->name('customer.orders.index');
    Route::get('orders/create', [CustomerOrderController::class, 'create'])->name('customer.orders.create');
    Route::post('orders', [CustomerOrderController::class, 'store'])->name('customer.orders.store');
});

// Admin routes
Route::prefix('admin')->group(function () {
    // Orders
    Route::get('orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::post('orders/{id}/status', [AdminOrderController::class, 'update'])->name('admin.orders.updateStatus');
    Route::delete('orders/{id}', [AdminOrderController::class, 'destroy'])->name('admin.orders.destroy');

    // Products
    Route::post('products', [AdminOrderController::class, 'storeProduct'])->name('admin.products.store');
    Route::post('products/{id}/toggle-status', [AdminOrderController::class, 'toggleProductStatus'])->name('admin.products.toggleStatus');
});
Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])
    ->name('admin.products.destroy');

// Product routes
Route::prefix('admin')->name('admin.')->group(function() {

    Route::post('/products/store', [AdminOrderController::class, 'storeProduct'])->name('products.store');

    // Add this update route
    Route::post('/products/update/{id}', [AdminOrderController::class, 'updateProduct'])->name('products.update');

    Route::post('/products/toggleStatus/{id}', [AdminOrderController::class, 'toggleProductStatus'])->name('products.toggleStatus');
});

// Manager Orders routes
Route::prefix('manager')->group(function () {
    // Orders
    Route::get('orders', [ManagerOrderController::class, 'index'])->name('manager.orders.index');
    Route::post('orders/{id}/status', [ManagerOrderController::class, 'updateStatus'])->name('manager.orders.updateStatus');
    Route::delete('orders/{id}', [ManagerOrderController::class, 'destroy'])->name('manager.orders.destroy');

    // Products
    Route::post('products', [ManagerOrderController::class, 'storeProduct'])->name('Manager.products.store');
    Route::post('products/{id}/toggle-status', [ManagerOrderController::class, 'toggleProductStatus'])->name('admin.products.toggleStatus');
});
// Product routes
Route::prefix('manager')->name('manager.')->group(function() {

    Route::post('/products/store', [ManagerOrderController::class, 'storeProduct'])->name('products.store');

    // Add this update route
    Route::post('/products/update/{id}', [ManagerOrderController::class, 'updateProduct'])->name('products.update');

    Route::post('/products/toggleStatus/{id}', [ManagerOrderController::class, 'toggleProductStatus'])->name('products.toggleStatus');
});


use App\Http\Controllers\Customer\CustomerProductController;

// Inside the 'customer' middleware/group if you have one
Route::get('products/{id}', [CustomerProductController::class, 'show'])
     ->name('customer.products.show');

Route::match(['put', 'patch'], 'admin/orders/{id}', [AdminOrderController::class, 'update'])
     ->name('admin.orders.update');
Route::match(['put', 'patch'], 'orders/{id}', [ManagerOrderController::class, 'updateStatus'])
     ->name('manager.orders.update');


// Route already defined above with correct method

// Optional: You may also want a route for viewing all orders
Route::get('/admin/orders', [AdminOrderController::class, 'index'])
     ->name('admin.orders.index');


// CUSTOMER SUPPORT
Route::prefix('customer')->middleware(['auth'])->name('customer.')->group(function () {

    // Support Center
    Route::get('support', [CustomerSupportController::class, 'index'])
        ->name('support.index');

    Route::post('support', [CustomerSupportController::class, 'store'])
        ->name('support.store');

    // AJAX routes
    Route::get('support/ajax/ticket/{id}', [CustomerSupportController::class, 'ajaxTicket'])
        ->name('support.ajax.ticket');

    Route::post('support/ajax/reply/{id}', [CustomerSupportController::class, 'ajaxReply'])
        ->name('support.ajax.reply');

});
///
// ------------------ Admin Support Routes ------------------
Route::prefix('admin/support')->name('admin.support.')->middleware(['auth'])->group(function() {
    Route::get('/', [AdminSupportController::class, 'index'])->name('index');
    Route::post('/status/{id}', [AdminSupportController::class, 'updateStatus'])->name('status');
    Route::post('/reply/{id}', [AdminSupportController::class, 'reply'])->name('reply');
    Route::post('/category/store', [AdminSupportController::class, 'storeCategory'])->name('category.store');
    Route::delete('/category/{id}', [AdminSupportController::class, 'destroyCategory'])->name('category.destroy');
    Route::post('/assign/{id}', [AdminSupportController::class, 'assignTicket'])->name('assign');
    
    // AJAX Helpers
    Route::get('/ticket/{id}', [AdminSupportController::class, 'ajaxTicket'])->name('ajax-ticket');
    Route::get('/logs/{id}', [AdminSupportController::class, 'ajaxLogs'])->name('ajax-logs');
    
    // Reports
    Route::get('/report/pdf', [AdminSupportController::class, 'exportPdf'])->name('report.pdf');
    Route::get('/report/excel', [AdminSupportController::class, 'exportExcel'])->name('report.excel');
});

// MANAGER SUPPORT
Route::prefix('manager')->name('manager.')->middleware(['auth'])->group(function () {
    Route::get('support', [\App\Http\Controllers\Manager\ManagerSupportController::class, 'index'])
        ->name('support.index');

    Route::post('support/reply/{id}', [\App\Http\Controllers\Manager\ManagerSupportController::class, 'reply'])
        ->name('support.reply');

    Route::post('support/status/{id}', [\App\Http\Controllers\Manager\ManagerSupportController::class, 'changeStatus'])
        ->name('support.status');

    Route::get('support/ajax/ticket/{id}', [\App\Http\Controllers\Manager\ManagerSupportController::class, 'ajaxTicket'])
        ->name('support.ajax.ticket');

    Route::get('support/ajax/logs/{id}', [\App\Http\Controllers\Manager\ManagerSupportController::class, 'ajaxLogs'])
        ->name('support.ajax.logs');
});


use App\Http\Controllers\Admin\AdminEmployeePerformanceController;

Route::get('admin/employee/performance', [AdminEmployeePerformanceController::class, 'index'])
    ->name('admin.performance.index');
use App\Http\Controllers\Manager\ManagerEmployeePerformanceController;

Route::get('manager/employee/performance', [ManagerEmployeePerformanceController::class, 'index'])
    ->name('manager.performance.index');

Route::get('/admin/orders/report', [AdminOrderController::class, 'generateReport'])->name('admin.orders.report');
Route::get('/admin/orders/report/pdf', [AdminOrderController::class, 'generateReportPDF'])->name('admin.orders.report.pdf');
Route::get('/admin/orders/report/excel', [AdminOrderController::class, 'generateReportExcel'])->name('admin.orders.report.excel');

//
Route::prefix('admin')->name('admin.')->group(function () {
    // Settings page
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');

    // Settings actions
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::post('/store', [SettingController::class, 'store'])->name('store');
        Route::post('/update-permissions', [SettingController::class, 'updatePermissions'])->name('updatePermissions');
        Route::post('/update-user-role', [SettingController::class, 'updateUserRole'])->name('updateUserRole');
    });
});


// routes/web.php


Route::prefix('manager')->name('manager.')->group(function () {
    Route::get('invoice/payment/{id}', [ManagerInvoiceController::class, 'payment'])
        ->name('invoice.payment');
});
Route::post('/manager/invoice/store', [ManagerInvoiceController::class, 'store'])
    ->name('manager.invoice.store');


    //
use App\Http\Controllers\Admin\AdminInvoiceController;
// In routes/web.php
Route::prefix('admin')->name('admin.')->group(function () {
    // Custom route for stock_out URL
    Route::get('stock_out/invoice', [AdminInvoiceController::class, 'create'])->name('stockout.invoice.create');
    
    // Save invoice with payment
    Route::post('stock_out/invoice/save', [AdminInvoiceController::class, 'saveInvoice'])->name('stockout.invoice.save');

    // PDF
    Route::get('invoices/{id}/pdf', [AdminInvoiceController::class, 'pdf'])->name('invoices.pdf');

    // Resource routes
    Route::resource('invoices', AdminInvoiceController::class)->except(['create']);
});


// ------------------ Admin Announcement Routes ------------------
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function() {
    Route::get('/announcements', [App\Http\Controllers\Admin\AnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcements', [App\Http\Controllers\Admin\AnnouncementController::class, 'store'])->name('announcements.store');
    Route::put('/announcements/{id}', [App\Http\Controllers\Admin\AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{id}', [App\Http\Controllers\Admin\AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    Route::post('/announcements/{id}/toggle', [App\Http\Controllers\Admin\AnnouncementController::class, 'toggleStatus'])->name('announcements.toggle');
});

// routes/web.php
Route::middleware('auth')->group(function () {
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'notificationsPage'])->name('notifications.page');
    Route::get('/notifications/read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::post('/notifications/clear-all', [NotificationController::class, 'clearAll'])->name('notifications.clearAll');
    Route::post('/notifications/dismiss/{id}', [NotificationController::class, 'dismissAnnouncement'])->name('notifications.dismiss');

    // Messages
    Route::get('/messages', [NotificationController::class, 'messagesPage'])->name('messages.page');
    Route::get('/messages/read/{id}', [NotificationController::class, 'markMessageAsRead'])->name('messages.read');
});
use App\Http\Controllers\ContactController;
Route::post('/contact-submit', [App\Http\Controllers\ContactController::class, 'submit'])->name('contact.submit');

use App\Http\Controllers\SendMessageController;

Route::post('/contact/send', [SendMessageController::class, 'send'])->name('contact.send');

Route::prefix('admin')->middleware(['auth'])->group(function() {

    Route::get('messages', [SendMessageController::class, 'index'])->name('admin.messages.index');
    Route::get('messages/ajax/{id}', [SendMessageController::class, 'ajaxDetails'])->name('admin.messages.ajax-details');
    Route::get('messages/{id}', [SendMessageController::class, 'show'])->name('admin.messages.show');
    Route::delete('messages/{id}', [SendMessageController::class, 'destroy'])->name('admin.messages.destroy');
    Route::post('messages/{id}/reply', [SendMessageController::class, 'storeReply'])->name('admin.messages.reply');

    Route::post('messages/mark-all-read', [SendMessageController::class, 'markAllRead'])->name('admin.messages.markAllRead');
    Route::post('messages/{id}/mark-read', [SendMessageController::class, 'markRead'])->name('admin.messages.markRead');

});



require __DIR__.'/auth.php';
