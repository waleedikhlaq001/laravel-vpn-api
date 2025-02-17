<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/dusk', function () {
    return view('welcome');
});
Route::get('/login',[AuthController::class, 'showLogin'])->name('login');
Route::post('/loginsubmit', [AuthController::class, 'login'])->name('login.attempt');

Route::get('/recover', [ForgotPasswordController::class, 'showPasswordRecovery'])->name('password.recover');

Route::get('/reset-password/{token}', function (string $token) {
    return inertia('Auth/ResetPassword', ['token' => $token]);
})->name('password.reset');
Route::post('/reset-password',[ForgotPasswordController::class, 'restPassword']);

Route::get('/register',[AuthController::class, 'showRegister']);
Route::post('/registersubmit',[AuthController::class, 'register']);

Route::get('/email/verify', function () {
    return inertia('Auth/VerifyEmail');
})->middleware(['auth'])->name('verification.notice');

Route::post('/email/verification-notification', function (Illuminate\Http\Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return redirect('/login')->with('message', 'Email verified successfully!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/email/verify/{id}/{hash}', function (Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/profile')->with('message', 'Email verified successfully!');
})->middleware(['auth', 'signed'])->name('verification.verify');
// Auth::routes(['verify' => true]);

Route::get('/translations/{locale}.json', [HomeController::class,'getTranslations']);

Route::middleware(['auth','verified'])->group(function () {

    Route::get('/', [HomeController::class, 'index']);

    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile-update', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/search-users', [AuthController::class, 'searchUsers']);
    Route::post('/change-user-status', [AuthController::class, 'changeStatus']);
    Route::post('/change-user-paid-status', [AuthController::class, 'changePaidStatus']);
    Route::get('/manage-group',[GroupController::class, 'index']);
    Route::get('/updatestatus/{userid}/{groupid}',[GroupController::class, 'updateStatus']);
    Route::get('/removeuser/{userid}/{groupid}',[GroupController::class, 'removeUser']);


    Route::get('/content',[PostController::class, 'mycontent']);
    Route::get('/new-record', [PostController::class, 'addPost']);
    Route::post('/add-record', [PostController::class, 'store']);
    Route::get('/update-record/{id}', [PostController::class, 'edit']);
    Route::get('/record-detail/{id}', [PostController::class, 'detail']);
    Route::post('/update-record', [PostController::class, 'update']);
    Route::get('/remove-record/{id}', [PostController::class, 'destory']);
    Route::post('/posts/{post}/favorite', [PostController::class, 'favorite'])->name('favorite');
    Route::delete('/posts/{post}/unfavorite', [PostController::class, 'unfavorite'])->name('unfavorite');
    Route::get('/favorites',[PostController::class, 'allfavorite']);
    Route::get('/share/{id}',[PostController::class, 'sharePost']);
    Route::post('/share-content',[PostController::class, 'share']);
    Route::post('/search-record',[PostController::class, 'search']);


    Route::get('/private-content/{id}', [PostController::class, 'privatePosts']);
    Route::get('/public-content/{id}', [PostController::class, 'privatePosts']);

    Route::get('/add-group', [GroupController::class, 'groupAdd']);
    Route::post('/create-group', [GroupController::class, 'groupStore']);
    Route::get('/join-group', [GroupController::class, 'joinGroup']);
    Route::get('/applygroup/{id}', [GroupController::class, 'applyGroup']);
    Route::post('/assign-group', [GroupController::class, 'assignGroup']);
    Route::get('/detach-group/{shared_id}/{id}', [GroupController::class, 'detachGroup']);
    Route::post('/group-update', [GroupController::class, 'groupUpdate']);
    Route::post('/add-user-to-group', [GroupController::class, 'addUserGroup']);
    Route::post('/update-user-role', [GroupController::class, 'updateUserRole']);

    Route::get('/users-list', [AuthController::class, 'userList']);
    Route::get('/impersonate/{id}', [AuthController::class, 'start']);
    Route::get('/stop-impersonating', [AuthController::class, 'stop']);
    Route::get('/logout', [AuthController::class, 'destroy'])
    ->name('logout');
});

