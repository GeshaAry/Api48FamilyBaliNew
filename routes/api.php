<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MemberJKT48Controller;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\ExcelController;
use App\Http\Controllers\Api\MemberGroupController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\GalleryController;


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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// MEMBER JKT48
Route::resource('member', MemberJKT48Controller::class);
Route::post('member/{member_id}', [MemberJKT48Controller::class, 'update']);
Route::get('allmember', 'App\Http\Controllers\Api\MemberJKT48Controller@AllMember');
Route::get('birthdaymember', 'App\Http\Controllers\Api\MemberJKT48Controller@BirthdayMember');
Route::get('showmember/{member_id}', 'App\Http\Controllers\Api\MemberJKT48Controller@show');

// ADMIN
Route::post('loginadmin','App\\Http\\Controllers\\Api\AdminController@login');
Route::resource('admin', AdminController::class);
Route::post('admin/{admin_id}', [AdminController::class, 'update']);

// USER
Route::resource('user', MemberGroupController::class);
Route::post('importexcel','App\\Http\\Controllers\\Api\ExcelController@importMemberGroupsDoc');
Route::post('daftar', [MemberGroupController::class, 'DaftarUlang']);
Route::post('pictureuser/{member_id_groups}', [MemberGroupController::class, 'updateProfilePictureUser']);
Route::post('memberuser/{member_id_groups}', [MemberGroupController::class, 'updateMemberUser']);
Route::post('changepassword/{member_id_groups}', [MemberGroupController::class, 'updatePassword']);
Route::post('login','App\\Http\\Controllers\\Api\MemberGroupController@login');

// ARTICLE
Route::resource('article', ArticleController::class);
Route::post('article/{article_id}', [ArticleController::class, 'update']);

//GALLERY
Route::resource('gallery', GalleryController::class);



