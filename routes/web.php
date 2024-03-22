<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\Portal\TagController;
use App\Http\Controllers\Portal\UserController;
use App\Http\Controllers\Portal\GroupController;
use App\Http\Controllers\Portal\ImageController;
use App\Http\Controllers\Portal\OrderController;
use App\Http\Controllers\Portal\ContactController;
use App\Http\Controllers\Portal\ProductController;
use App\Http\Controllers\Portal\SettingController;
use App\Http\Controllers\Portal\CategoryController;
use App\Http\Controllers\Portal\CustomerController;
use App\Http\Controllers\Portal\PasswordController;
use App\Http\Controllers\Portal\DashboardController;
use App\Http\Controllers\Portal\UserdashboardController;
use App\Http\Controllers\Portal\ApplicationController;
use App\Http\Controllers\Portal\DispatchNotesController;
use App\Http\Controllers\Portal\PermissionController;
use App\Http\Controllers\Portal\ProjectController;
use App\Http\Controllers\Portal\TaskController;
use App\Http\Controllers\Portal\SubTaskController;
use App\Http\Controllers\Portal\NotificationController;



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
    return view('auth.login');
});


Route::group(['prefix' => '/admin', 'middleware' => ['auth']], function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart/orders-and-users/{start_date}/{end_date}', [DashboardController::class, 'orderAndUsers']);
   
    

    Route::group(['prefix'=>'/permission'],function(){
        Route::get('/', [PermissionController::class,'index'])->name('permission.index');
        Route::get('/add',[PermissionController::class,'add'])->name('permission.add');
        Route::post('/store', [PermissionController::class, 'store'])->name('permission.store');
        Route::get('/list', [PermissionController::class, 'list'])->name('permission.list');
        Route::get('/edit/{id}', [PermissionController::class, 'edit'])->name('permission.edit');
        Route::get('/delete/{id}', [PermissionController::class, 'delete'])->name('permission.delete');
        Route::post('/update/{id}', [PermissionController::class, 'update'])->name('permission.update');
    });
    
    Route::group(['prefix'=>'/notification'],function(){
        Route::get('/', [NotificationController::class,'index'])->name('notification.index');
        Route::get('/add',[NotificationController::class,'add'])->name('notification.add');
        Route::post('/store', [NotificationController::class, 'store'])->name('notification.store');
        Route::get('/list', [NotificationController::class, 'list'])->name('notification.list');
        Route::get('/edit/{id}', [NotificationController::class, 'edit'])->name('notification.edit');
        Route::get('/delete/{id}', [NotificationController::class, 'delete'])->name('notification.delete');
        Route::post('/update/{id}', [NotificationController::class, 'update'])->name('notification.update');
    });

    Route::group(['prefix' => '/user'], function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::get('/add', [UserController::class, 'add'])->name('user.add');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
        Route::get('/list', [UserController::class, 'list'])->name('user.list');
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/delete/{id}', [UserController::class, 'delete'])->name('user.delete');
        Route::post('/update/{id}', [UserController::class, 'update'])->name('user.update');
        Route::post('/assign-permission', [UserController::class, 'assign_permission'])->name('user.assign_permission');
        Route::get('/get-permissions/{userId}', [UserController::class, 'get_permission'])->name('user.getPermission');
    });

    Route::group(['prefix' => '/project'], function () {
        Route::get('/', [ProjectController::class, 'index'])->name('project.index');
        Route::get('/add', [ProjectController::class, 'add'])->name('project.add');
        Route::get('/edit/{id}', [ProjectController::class, 'edit'])->name('project.edit');
        Route::get('/list', [ProjectController::class, 'list'])->name('project.list');
        Route::post('/store', [ProjectController::class, 'store'])->name('project.store');
        Route::get('/delete/{id}', [ProjectController::class, 'delete'])->name('project.delete');
        Route::post('/update/{id}', [ProjectController::class, 'update'])->name('project.update');
    });

    Route::group(['prefix' => '/task'], function () {
        Route::get('/', [TaskController::class, 'index'])->name('task.index');
        Route::get('/assignedtask', [TaskController::class, 'assignedtask'])->name('task.assignedtask');
        Route::get('/add', [TaskController::class, 'add'])->name('task.add');
        Route::get('/edit/{id}', [TaskController::class, 'edit'])->name('task.edit');
        Route::get('/editcollab/{id}', [TaskController::class, 'editcollab'])->name('task.editcollab');
        Route::get('/list', [TaskController::class, 'list'])->name('task.list');
        Route::post('/store', [TaskController::class, 'store'])->name('task.store');
        Route::get('/completed', [TaskController::class, 'completed'])->name('task.completed');
        Route::get('/incompleted', [TaskController::class, 'incompleted'])->name('task.incompleted');
        Route::get('/delete/{id}', [TaskController::class, 'delete'])->name('task.delete');
        Route::post('/update/{id}', [TaskController::class, 'update'])->name('task.update');
        Route::post('/updatecollaborator/{id}', [TaskController::class, 'updatecollaborator'])->name('task.updatecollaborator');
        Route::get('/{id}', [TaskController::class, 'subtask_list'])->name('task.subtask_list');
    });

    Route::group(['prefix' => '/userdashboard'], function () {
        Route::get('/index/{id}', [UserdashboardController::class, 'index'])->name('user.dashboard');
        Route::get('/userincompleted/{id}', [UserdashboardController::class, 'userincompleted'])->name('userdashboard.userincompleted');
        Route::get('/useralltasks/{id}', [UserdashboardController::class, 'useralltasks'])->name('userdashboard.useralltasks');
        Route::get('/completed/{id}', [UserdashboardController::class, 'completedtasks'])->name('userdashboard.completedtasks');
    });

    Route::group(['prefix' => '/subtask'], function () {
        Route::get('/', [SubTaskController::class, 'index'])->name('subtask.index');
        Route::get('/add', [SubTaskController::class, 'add'])->name('subtask.add');
        Route::get('/edit/{id}', [SubTaskController::class, 'edit'])->name('subtask.edit');
        Route::get('/list', [SubTaskController::class, 'list'])->name('subtask.list');
        Route::post('/store', [SubTaskController::class, 'store'])->name('subtask.store');
        Route::get('/delete/{id}', [SubTaskController::class, 'delete'])->name('subtask.delete');
        Route::post('/update/{id}', [SubTaskController::class, 'update'])->name('subtask.update');
        //Route::get('/listsub', [SubTaskController::class, 'listsub'])->name('subtask.listsub');
        Route::get('/get-task-progress', [SubTaskController::class, 'getTaskProgress'])->name('subtask.getTaskProgress');

        Route::get('user-activity/{id}',[SubTaskController::class, 'user_activity'])->name('subtask.user-activity');
        Route::get('/{id}', [SubTaskController::class, 'activity'])->name('task.activity');
    });

    Route::group(['prefix' => '/setting'], function () {
        Route::get('/', [SettingController::class, 'index'])->name('setting.index');
    });

    Route::group(['prefix' => '/password'], function () {
        Route::post('/change', [PasswordController::class, 'change'])->name('password.change');
    });

    Route::group(['prefix' => '/image'], function () {
        Route::post('/store', [ImageController::class, 'store'])->name('image.store');
    });
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/invoice/{id}', [OrderController::class, 'invoice'])->name('invoice');
Route::get('verify_email/{email}', [UserController::class, 'verify_email'])->name('verify_email');

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
