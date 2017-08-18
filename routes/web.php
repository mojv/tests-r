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

Route::group(['middleware' => ['web', 'locale', 'fw-block-bl']], function () {

    Route::get('/', function () {
        return redirect('/login');
    });

    Route::get('/dashboard', ['as' => 'dashboard', 'uses' =>function () {
        return view('board/test');
    }])->middleware('auth');

    Route::get('/profile', ['as' => 'profile', 'uses' =>'UpdateUserController@profile']);
    Route::patch('/profile', ['as' => 'profileUpdate', 'uses' =>'UpdateUserController@update']);

    //--------- Language -------------
    Route::get('lang/{locale}', function ($locale) {
        Session::put('locale', $locale);
        return back();
    })->name('lang');

    //---------- Create Form -----------
    Route::get('/createform', ['as' => 'createForm', 'uses' =>'FormController@createForm']);
    Route::post('/createform', ['as' => 'checkName', 'uses' =>'FormController@checkName']);
    Route::put('/createform/{form}/delete', ['as' => 'deleteFormcoords', 'uses' =>'FormController@deleteFormcoords']);
    Route::post('/createform/{form}/update', ['as' => 'updateFormcoords', 'uses' =>'FormController@updateFormcoords']);

    //---------- Update Form -----------
    Route::get('/updateform', ['as' => 'updateForm', 'uses' =>'FormController@updateForm']);
    Route::patch('/updateform', ['as' => 'editName', 'uses' =>'FormController@editName']);
    Route::get('/updateform/{form}/delete', ['as' => 'deleteForm', 'uses' =>'FormController@deleteForm']);
    Route::get('/updateform/{form}/edit', ['as' => 'editForm', 'uses' =>'FormController@editForm']);
    Route::patch('/uploadFormfile', ['as' => 'uploadFormfile', 'uses' =>'FormController@uploadFormfile']);
    Route::post('/shareForm', ['as' => 'shareForm', 'uses' =>'FormController@shareForm']);
    Route::post('/shareFormCreate', ['as' => 'shareFormCreate', 'uses' =>'FormController@shareFormCreate']);
    Route::post('/shareFormDelete', ['as' => 'shareFormDelete', 'uses' =>'FormController@shareFormDelete']);

    //-----------Read Forms----------
    Route::get('/readform', ['as' => 'formList', 'uses' =>'FormController@formList']);
    Route::get('/readform/{form}/read', ['as' => 'readForm', 'uses' =>'FormController@readForm']);
    Route::get('/readSharedform/{form}/read', ['as' => 'readSharedForm', 'uses' =>'FormController@readSharedForm']);
    Route::get('/shareFormDelete/{form}/', ['as' => 'stopShareForm', 'uses' =>'FormController@stopShareForm']);
    Route::get('/readtemplate/{form}/read', ['as' => 'appReadForm', 'uses' =>'FormController@appReadForm']);
    Route::get('/readtemplate', ['as' => 'templateList', 'uses' =>'FormController@templateList']);

    //-----------Downloads--------
    Route::get('/templates', ['as' => 'templates', 'uses' =>'FormController@templates']);
    Route::get('/software', ['as' => 'software', 'uses' =>'FormController@software']);

    //-----------OCR Language--------
    Route::get('/OCR-language', ['as' => 'ocrLanguage', 'uses' =>'FormController@ocrLanguage']);

    //-----------OCR Language--------
    Route::get('/donate', ['as' => 'donate', 'uses' =>'FormController@donate']);
    Route::get('/forum', ['as' => 'forum', 'uses' =>'FormController@forum']);

    //-----------Students-----------
    Route::get('/myStudents', ['as' => 'myStudents', 'uses' =>'ClassroomController@myStudents']);
    Route::post('/myStudents', ['as' => 'createStudent', 'uses' =>'ClassroomController@createStudent']);
    Route::patch('/myStudents/{student}/', ['as' => 'updateStudent', 'uses' =>'ClassroomController@updateStudent']);
    Route::delete('/myStudents/', ['as' => 'deleteStudent', 'uses' =>'ClassroomController@deleteStudent']);
    Route::get('/deleteAllStudents/', ['as' => 'deleteAllStudents', 'uses' =>'ClassroomController@deleteAllStudents']);
    Route::post('/addStudentList', ['as' => 'addStudentList', 'uses' =>'ClassroomController@addStudentList']);
    Route::get('/filterStudents', ['as' => 'filterStudents', 'uses' =>'ClassroomController@filterStudents']);

    //-----------Classes-----------
    Route::get('/myClasses', ['as' => 'myClasses', 'uses' =>'ClassroomController@myClasses']);
    Route::post('/myClasses', ['as' => 'createClass', 'uses' =>'ClassroomController@createClass']);
    Route::patch('/myClasses/{class}/', ['as' => 'updateClass', 'uses' =>'ClassroomController@updateClass']);
    Route::delete('/myClasses/', ['as' => 'deleteClass', 'uses' =>'ClassroomController@deleteClass']);
    Route::get('/deleteAllClasses/', ['as' => 'deleteAllClasses', 'uses' =>'ClassroomController@deleteAllClasses']);
    Route::get('/filterClasses', ['as' => 'filterClasses', 'uses' =>'ClassroomController@filterClasses']);
    Route::post('/createExam', ['as' => 'createExam', 'uses' =>'ClassroomController@createExam']);

    Auth::routes();

    Route::get('/home', 'HomeController@index');

});
