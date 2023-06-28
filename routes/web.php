<?php

use App\Http\Controllers\Admin\AppController;
use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DownloadController;
use App\Http\Controllers\Admin\FCMController;
use App\Http\Controllers\Admin\IssueController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ClaimEntry\ClaimController;
use App\Http\Controllers\ClaimEntry\ClaimFileController;
use App\Http\Controllers\ClaimEntry\CommentController;
use App\Http\Controllers\SystemAdmin\CompanyController;
use App\Http\Controllers\SystemAdmin\NotificationsController;
use App\Http\Controllers\SystemAdmin\SchemeAdminController;
use App\Http\Controllers\SystemAdmin\SchemeController;
use App\Http\Controllers\SystemAdmin\StaffAccountController;
use App\Models\Claim;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect('/login');
});


Route::group(['middleware' => ['lisc']], function(){

    Route::get('/login', [AuthController::class, 'loginPage'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticateUser'])->name('authenticate');
    Route::get('/forgot-password', [AuthController::class, 'forgotPasswordPage'])->name('forgot-password');
    Route::get('/logout', [AuthController::class, 'logoutUser'])->name('logout');


    //shared authenticated routes WITH COMMON ROLES
    Route::group(['middleware' => ['auth','role:system-admin|claim-entry|audit|accounting|front-desk']], function () {
        Route::get('/dashboard',[DashboardController::class, 'index'])->name('home');
        Route::get('/search-claim', [ClaimController::class, 'searchClaim'])->name('search-claim');
        Route::get('/claims', [ClaimController::class, 'getClaims'])->name('get-claims');
        Route::get('/claim', [ClaimController::class, 'findClaim'])->name('find-claim');
        Route::get('scheme-claims', [ClaimController::class, 'schemeClaims'])->name('scheme-claims');
        Route::post('/report-issue', [IssueController::class, 'saveClaimIssue'])->name('new-issue');
        Route::get('quick-search', [SearchController::class, 'quickSearch'])->name('quick-search');
        Route::get('/issue-review', [IssueController::class, 'reviewClaimIssue'])->name('review-issue');
        Route::get('/download/{id}/{claimid}', [DownloadController::class, 'downloadClaimFiles'])->name('download-claim-files');
        Route::get('/reviewfiles-download/{issueid}/', [DownloadController::class, 'downloadReviewFiles'])->name('download-review-files');
        Route::get('/general-search', [SearchController::class,'generalSearch'])->name('general-search');
        Route::get('/customers', [CustomerController::class, 'getCustomers']);
        Route::get('/search-company', [CompanyController::class, 'searchCompany'])->name('search-company');
        Route::get('/schemes',[SchemeController::class, 'schemes'])->name('schemes');
        Route::get('/audit',[AuditController::class, 'audit'])->name('audit');
        Route::post('/audit',[AuditController::class, 'uploadAuditFiles'])->name('audit');
        Route::get('/audited-claims',[AuditController::class, 'getAuditedClaims'])->name('audit-claims');
        Route::post('/issue-review', [IssueController::class, 'resolveIssue'])->name('resolve-issue');
        Route::post('/report-issue-on-file', [IssueController::class, 'reportIssueonFile'])->name('issue-on-file');
        Route::get('/unprocessed-claims', [ClaimController::class, 'getUnProcessedClaims'])->name('un-processed');
        Route::get('/invalid-claims', [ClaimController::class, 'invalidClaims'])->name('invalid');
        Route::post('/update-valid-status', [ClaimController::class, 'updateClaimState'])->name('validate-state');
        Route::get('/claim-with-issues', [ClaimController::class, 'getClaimsWithIssues'])->name('claim-with-issues');
        Route::get('/company-reports', [ReportsController::class, 'companyReports'])->name('company-reports');
        Route::get('/scheme-reports', [ReportsController::class, 'schemeReports'])->name('scheme-reports');
        Route::get('/excel-reports', [ReportsController::class, 'exportExcel'])->name('export-excel');
        Route::get('/reports-breakdown', [ReportsController::class, 'getReportBreakdown']);
        Route::get('/download-excel-format', [DownloadController::class, 'downloadExcelClaimFormat'])->name('download-claim-format');
        Route::get('/scheme-audited-claims',[SchemeController::class, 'getSchemeAuditedClaims'])->name('scheme-audited');
        Route::post('/receive-claim', [ClaimController::class, 'receiveClaim'])->name('receive-claim');
        Route::post('/delete-claim', [ClaimController::class, 'deleteClaim'])->name('delete-claim');
        Route::post('/delete-single-claim-file', [ClaimFileController::class, 'deleteClaimFile'])->name('delete-claim-file');


        Route::get('/success', [DashboardController::class, 'successPage'])->name('success-page');  
        Route::get('/notifications', [NotificationsController::class, 'getUnreadNotifications'])->name('unread-notifications');
        Route::get('/read-notifications', [NotificationsController::class, 'getReadNotifications'])->name('read-notifications');
        Route::post('/mark-read', [NotificationsController::class, 'markeAsRead'])->name('mark-as-read');
        Route::post('/edit-comment',[CommentController::class, 'updateComment'])->name('edit-comment');
        Route::post('/mark-validity-status', [CommentController::class, 'updateClaimValidityStatus'])->name('validity-status');


        //FCM TOKEN UPDATE ROUTES
        Route::post('/update-fcm-token',[FCMController::class, 'updateFCMToken'])->name('update-fcm-token');
    });

    Route::group(['middleware' => ['auth','role:system-admin|claim-entry']], function () { 
        Route::get('/processed-files', [ClaimController::class, 'processedFiles'])->name('processed-files');
        Route::post('/processed-files', [ClaimController::class, 'saveProcessedClaimFiles'])->name('save-processed-claim-files');
    });


    //SYSTEM ADMIN AND CLAIM ENTRY ROLES ROUTES
    Route::group(['middleware' => ['auth','role:system-admin|claim-entry|front-desk']], function () { 
        Route::get('/new-claim', [ClaimController::class, 'newClaim'])->name('new-claim');
        Route::get('claim-files', [ClaimController::class, 'claimFiles'])->name('claim-files');
        Route::post('claim-files', [ClaimController::class, 'saveClaimFiles'])->name('save-claim-files');
        Route::post('/new-claim', [ClaimController::class, 'saveNewClaim'])->name('save-claim');
    });


    //SYSTEM ADMIN ROLES ROUTES
    Route::group(['middleware' => ['auth','role:system-admin']], function () {
        Route::get('/new-staff',[StaffAccountController::class, 'newStaff'])->name('new-staff');
        Route::post('/new-staff',[StaffAccountController::class, 'saveNewStaff'])->name('save-staff');
        Route::get('/all-staffs', [StaffAccountController::class, 'allStaffs'])->name('all-staff');
        Route::get('/new-company',[CompanyController::class, 'newCompany'])->name('new-company');
        Route::post('/new-company',[CompanyController::class, 'saveCompany'])->name('save-company');
        Route::get('/new-scheme',[SchemeController::class, 'newScheme'])->name('new-scheme');
        Route::post('/new-scheme',[SchemeController::class, 'saveScheme'])->name('save-scheme');
        Route::get('/edit-staff',[StaffAccountController::class, 'editStaff'])->name('edit-staff');
        Route::post('/edit-staff',[StaffAccountController::class, 'saveNewStaffInfo'])->name('save-new-staff');
        Route::post('/update-staff-account-state', [StaffAccountController::class, 'updateStaffAccountState']);
    });

    //SYSTEM ADMIN ROLES ROUTES
    Route::group(['middleware' => ['auth','role:accounting']], function () {
    Route::post('/cheque-no-entry', [SchemeAdminController::class, 'enterChequeNo'])->name('cheque-emtry');
    Route::post('/transfered-to-bank', [SchemeAdminController::class, 'transferTobank'])->name('transfer-bank');
    });

});
Route::get('/expired', [AppController::class, 'expireApp'])->name('expired');


/**
 * roles
 * php artisan permission:create-role system-admin
 * php artisan permission:create-role claim-entry
 * php artisan permission:create-role audit
 * php artisan permission:create-role accounting
 * php artisan permission:create-role front-desk
 * 
 */