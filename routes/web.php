<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
// Route::get('/home', 'HomeController@index')->name('home');

Route::get('/home', 'DashboardController');
Route::get('/all-tasks', 'DashboardController');
Route::get('/complete', 'DashboardController');
Route::get('/edit-task', 'DashboardController');
Route::get('/new-todo', 'DashboardController');

//Endpoint


Route::get('/getTasks', 'HomeController@getTasks');
Route::get('/getAll', 'HomeController@getAll');
Route::get('/dueTodayTasks', 'HomeController@dueTodayTasks');
Route::get('/getCompleted', 'HomeController@getCompleted');
Route::get('/getActivites', 'HomeController@getActivites');
Route::post('/saveTask', 'HomeController@saveTask');
Route::post('/updateTask', 'HomeController@updateTask');
Route::post('/changeStatus', 'HomeController@changeStatus');
Route::post('/changeMultipleStatus', 'HomeController@changeMultipleStatus');
Route::post('/deleteTasks', 'HomeController@deleteTasks');
Route::post('/deleteTask', 'HomeController@deleteTask');



Route::get('reset', function () {
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    return '<h1>Have cleared the env</h1>';
});
