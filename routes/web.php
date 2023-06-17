<?php

use App\Http\Controllers\LessonController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();
Route::group(['middleware' => ['role:Admin']], function () {
    Route::resource('users', UserController::class)->except('show');
    Route::get('/users/{id}/resetpassword', [UserController::class, 'resetpassword'])->name('users.resetpassword');
    Route::get('/users/jsontable', [UserController::class, 'jsontable'])->name('users.jsontable');
    Route::resource('lessons', LessonController::class)->except('show');
    Route::get('/lessons/jsontable', [LessonController::class, 'jsontable'])->name('lessons.jsontable');
    Route::resource('questions', QuestionController::class)->except('index', 'create', 'show');
    Route::get('/questions/index/{lessonId}', [QuestionController::class, 'index'])->name('questions.index');
    Route::get('/questions/create/{lessonId}', [QuestionController::class, 'create'])->name('questions.create');
    Route::get('/questions/jsontable', [QuestionController::class, 'jsontable'])->name('questions.jsontable');
});
