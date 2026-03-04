<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\RolePermission\PermissionController;
use App\Http\Controllers\Backend\RolePermission\RoleController;
use App\Http\Controllers\Backend\UserManagementController;
use App\Http\Controllers\Backend\CurrencyController;
use App\Http\Controllers\Backend\WebsiteSettingController;
use App\Http\Controllers\Backend\Member\ContributionController;
use App\Http\Controllers\Backend\Member\MemberContributionPaymentController;
use App\Http\Controllers\Backend\Treasurer\TreasurerController;
use App\Http\Controllers\Backend\Communication\AnnouncementEventController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ====================== FRONTEND ======================

// homepage
Route::get('/', function () {
    return to_route('login');
})->name('frontend.home');

//authentication
Route::match(['get', 'post'], 'login', [AuthController::class, 'login'])->name('login');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::match(['get', 'post'], 'sign-up', [AuthController::class, 'register'])->name('signup');
Route::match(['get', 'post'], 'forget-password', [AuthController::class, 'forgetPassword'])->name('forget.password');
Route::match(['get', 'post'], 'new-password', [AuthController::class, 'newPassword'])->name('new.password');
Route::match(['get', 'post'], 'password-reset', [AuthController::class, 'resetPassword'])->name('password.reset');
Route::get('resend-otp', [AuthController::class, 'resendOtp'])->name('resend.otp');

// google auth
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.handle.callback');

// ====================== BACKEND =======================

Route::prefix('admin')->as('backend.admin.')->middleware(['admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        // ====================== CONTRIBUTIONS ======================
        Route::prefix('contributions')->group(function () {
            Route::get('/', [ContributionController::class, 'index'])->name('contributions.index');
            Route::get('current/{user?}', [ContributionController::class, 'showCurrent'])->name('contributions.current');
            Route::get('member/{user}', [ContributionController::class, 'showMemberContributions'])->name('contributions.member.view');
            Route::match(['get','post'], 'edit/{id}', [ContributionController::class, 'edit'])->name('contributions.edit');
            Route::delete('delete/{id}', [ContributionController::class, 'delete'])->name('contributions.delete');
        });

        // ====================== MEMBER CONTRIBUTION PAYMENTS ======================
        Route::prefix('contributions/payments')->as('contributions.payments.')->group(function () {
            Route::get('/', [MemberContributionPaymentController::class, 'index'])->name('index');
            Route::get('create', [MemberContributionPaymentController::class, 'create'])->name('create');
            Route::post('pay', [MemberContributionPaymentController::class, 'pay'])->name('pay');
            Route::get('{contributionId}', [MemberContributionPaymentController::class, 'showContributionPayments'])->name('show');
        });

       // ====================== ADMIN CONTRIBUTION SETTINGS ======================
        Route::prefix('contributions')->group(function () {
            Route::get('settings', [ContributionController::class, 'settings'])->name('contributions.settings'); // shows the form
            Route::put('settings', [ContributionController::class, 'updateSettings'])->name('contributions.settings.update');// saves changes
            Route::get('settings/view', [ContributionController::class, 'viewSettings'])->name('contributions.settings.view');
        });
        
        // ====================== MPESA PAYMENTS ======================
        Route::post('/payment/stk-callback', [MemberContributionPaymentController::class, 'handleStkCallback']);
        Route::post('/mpesa/stk-push', [MemberContributionPaymentController::class, 'stkPush'])->name('mpesa.stk.push')->middleware(['auth']);
        Route::get('check-payment-status/{checkoutId}',[MemberContributionPaymentController::class, 'checkPaymentStatus'])->name('mpesa.check.status');

        // ====================== TREASURE ======================
        Route::prefix('treasurer')->as('treasurer.')->group(function () {
            Route::get('dashboard', [TreasurerController::class, 'dashboard'])->name('dashboard');
            Route::get('transactions', [TreasurerController::class, 'transactions'])->name('transactions');
            Route::get('expenses', [TreasurerController::class, 'expenses'])->name('expenses');
            Route::get('expenses/create', [TreasurerController::class, 'createExpense'])->name('expenses.create');
            Route::post('expenses/store', [TreasurerController::class, 'storeExpense'])->name('expenses.store');
            Route::get('reports', [TreasurerController::class, 'reports'])->name('reports');
        });

        // ====================== ANNOUNCEMENTS & EVENTS ======================
        Route::prefix('communications')->as('communications.')->group(function () {
            Route::get('announcements', [AnnouncementEventController::class, 'announcements'])->name('announcements');
            Route::get('announcements/create', [AnnouncementEventController::class, 'createAnnouncement'])->name('announcements.create');
            Route::post('announcements/store', [AnnouncementEventController::class, 'storeAnnouncement'])->name('announcements.store');

            Route::get('announcements/edit/{announcement}', [AnnouncementEventController::class, 'editAnnouncement'])->name('announcements.edit');
            Route::put('announcements/update/{announcement}', [AnnouncementEventController::class, 'updateAnnouncement'])->name('announcements.update');
            Route::delete('announcements/delete/{announcement}', [AnnouncementEventController::class, 'deleteAnnouncement'])->name('announcements.delete');
            
            Route::get('events', [AnnouncementEventController::class, 'events'])->name('events');
            Route::get('events/create', [AnnouncementEventController::class, 'createEvent'])->name('events.create');
            Route::post('events/store', [AnnouncementEventController::class, 'storeEvent'])->name('events.store');

            Route::get('events/edit/{event}', [AnnouncementEventController::class, 'editEvent'])->name('events.edit');
            Route::put('events/update/{event}', [AnnouncementEventController::class, 'updateEvent'])->name('events.update');
            Route::delete('events/delete/{event}', [AnnouncementEventController::class, 'deleteEvent'])->name('events.delete');
        });

        // ====================== CURRENCIES ======================
        Route::resource('currencies', CurrencyController::class);
        Route::get('currencies/default/{id}', [CurrencyController::class, 'setDefault'])->name('currencies.setDefault');

        // ====================== PROFILE ======================
        Route::get('profile', [DashboardController::class, 'profile'])->name('profile');
        Route::post('profile/update', [AuthController::class, 'update'])->name('profile.update');

        // ====================== USER MANAGEMENT ======================
        Route::prefix('users')->group(function () {
            Route::get('/', [UserManagementController::class, 'index'])->name('users');
            Route::get('suspend/{id}/{status}', [UserManagementController::class, 'suspend'])->name('user.suspend');
            Route::match(['get', 'post'], 'create', [UserManagementController::class, 'create'])->name('user.create');
            Route::match(['get', 'post'], 'edit/{id}', [UserManagementController::class, 'edit'])->name('user.edit');
            Route::get('delete/{id}', [UserManagementController::class, 'delete'])->name('user.delete');
        });

        // ====================== WEBSITE SETTINGS ======================
        Route::prefix('settings')->group(function () {
            Route::prefix('website')->group(function () {
                Route::controller(WebsiteSettingController::class)->prefix('general')->group(function () {
                    Route::get('/', 'websiteGeneral')->name('settings.website.general');
                    Route::post('update-info', 'websiteInfoUpdate')->name('settings.website.info.update');
                    Route::post('update-contacts', 'websiteContactsUpdate')->name('settings.website.contacts.update');
                    Route::post('update-social-links', 'websiteSocialLinkUpdate')->name('settings.website.social.link.update');
                    Route::post('update-style-settings', 'websiteStyleSettingsUpdate')->name('settings.website.style.settings.update');
                    Route::post('update-custom-css', 'websiteCustomCssUpdate')->name('settings.website.custom.css.update');
                    Route::post('update-notification-settings', 'websiteNotificationSettingsUpdate')->name('settings.website.notification.settings.update');
                    Route::post('update-website-status', 'websiteStatusUpdate')->name('settings.website.status.update');
                    Route::post('update-invoice-settings', 'websiteInvoiceUpdate')->name('settings.website.invoice.update');
                });

                Route::controller(RoleController::class)->prefix('roles')->group(function () {
                    Route::get('/', 'index')->name('roles');
                    Route::post('create', 'store')->name('roles.create');
                    Route::get('show/{id}', 'show')->name('roles.show');
                    Route::put('update/{id}', 'update')->name('roles.update');
                    Route::get('delete/{id}', 'destroy')->name('roles.delete');
                    Route::post('role-permission/{id}', 'updatePermission')->name('update.role-permissions');
                    Route::get('role-wise-permissions/{id?}', 'roleWisePermissions')->name('role-wise-permissions');
                });

                Route::controller(PermissionController::class)->prefix('permissions')->group(function () {
                    Route::get('/', 'index')->name('permissions');
                    Route::post('create', 'store')->name('permissions.store');
                    Route::put('update/{id}', 'update')->name('permissions.update');
                    Route::get('delete/{id}', 'destroy')->name('permissions.delete');
                });
        });
    });
});

// ====================== /BACKEND ======================

Route::get('clear-all', function () {
    Artisan::call('optimize:clear');
    return redirect()->back();
});

Route::get('storage-link', function () {
    Artisan::call('storage:link');
    return redirect()->back();
});

Route::get('test', [TestController::class, 'test'])->name('test');
