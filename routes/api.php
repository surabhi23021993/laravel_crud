<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

$api = app("Dingo\Api\Routing\Router");

$api->version("v1", function ($api) {

    // generate token
    $api->post("authenticate", "App\Http\Controllers\Auth\AuthController@authenticate");

    // user's api
    $api->get("users", "App\Http\Controllers\UserController@index");    
    $api->post("register/user", "App\Http\Controllers\UserController@create");
    $api->put("user/{id}/update", "App\Http\Controllers\UserController@update");
    $api->get("user/{id}/view", "App\Http\Controllers\UserController@view");
    
    //  document's api
    $api->post("document/upload", "App\Http\Controllers\DocumentController@upload");

    // change password api
    //$api->post("changePassword", "App\Http\Controllers\UserController@changePassword");

    
    // Company api
    $api->get("student_details", "App\Http\Controllers\StudentController@index");
    $api->get("student_details/{id}/view", "App\Http\Controllers\StudentController@view");
    $api->post("student_details/create", "App\Http\Controllers\StudentController@create");
    $api->post("student_details/update/{id}", "App\Http\Controllers\StudentController@update");
    $api->delete("student_details/delete/{id}", "App\Http\Controllers\StudentController@destroy");
    

    $api->post("upload_bgdoc", "App\Http\Controllers\StudentDocumentController@uploadStudentDocument");

    

});

$api->version("v4", ['middleware' => 'api.auth'], function ($api) {

});
