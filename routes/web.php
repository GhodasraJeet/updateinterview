<?php

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

Route::redirect('/','/login');

Auth::routes(['register'=>false,'reset' => false]);

Route::group(['middleware' => 'customauth'], function () {

    Route::get('/home', 'HomeController@index')->name('home');
    Route::post('search/student','HomeController@searchstudent')->name('student.search');
    Route::middleware('can:create-hr')->group(function(){
        // HR Routes
        Route::resource('hr', 'HrController');
        // Route::post('hr/search', 'HrController@searchhr')->name('hr.search');
    });

    // Notes routes
    Route::resource('notes', 'NotesController')->only(['index','create','store','edit','update','destroy']);
    Route::post('notes/favourite','NotesController@notefavourite')->name('note.favourite');
    // Search note routes
    Route::post('note/search','NotesController@searchnote')->name('note.search');

    // Policy Routes
    Route::post('updatepolicy/{id}','HomeController@updatepolicy')->name('update.policy');
    Route::post('savepolicy','HomeController@store')->name('save.policy');

    // Recruting Routes
    Route::resource('recrut','RecrutingController')->only(['index','store','show','destroy']);
    Route::post('stateupdate','RecrutingController@updateOrder')->name('recrut.updatestate');

    // Job Routes
    Route::resource('job', 'JobsController');
    // Search Job Routes
    Route::post('job/search', 'JobsController@searchjob')->name('job.search');


    // Profile Routes
    Route::get('profile','UserController@viewprofile')->name('profile.show');
    Route::post('profileupdate','UserController@updateprofile')->name('profile.update');




    // Fetch technology and State routes
    Route::get('fetchtechnology','HomeController@technology')->name('technology');
    Route::get('fetchstate','HomeController@state')->name('state');

    // Search Student Dashboard Page
    Route::post('studentsearch', 'HomeController@student')->name('studentsearch');

    // Search HR Page
    Route::post('hrsearch','HrController@fetch_hr')->name('hrsearch');

    // Search Job Page
    Route::post('jobsearch','JobsController@fetch_job')->name('jobsearch');

    // Multiple Job,HR,Student Delete
    Route::delete('delete-multiple-jobs','JobsController@deleteMultipleJobs')->name('deletemultiplejobs');
    Route::delete('delete-multiple-hrs','HrController@deleteMultipleHrs')->name('deletemultiplehrs');
    Route::delete('delete-multiple-students','HomeController@deleteMultipleStudents')->name('deletemultiplestudents');

    // User Change Password
    Route::get('password','UserController@password')->name('password');
    Route::post('password','UserController@update')->name('UpdatePassword');




    // faltu
    Route::post('updatehr','HrController@updatehr')->name('updatehr');

});
