<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('register',"AuthController@register");
});

Route::post('upload', 'UploadController@upload');
Route::post('download', 'UploadController@download');

//获取所有教师列表
Route::get('teachers', function () {
    $res = array();
    $list = App\User::all()->where('role', 2);
    foreach ($list as $l) {
        $res[] = array(
            'id' => $l->id,
            'name' => $l->name
        );
    }
    return response()->json($res);
});

//获取所有学生列表
Route::get('students', function () {
    $res = array();
    $list = App\User::all()->where('role', 1);
    foreach ($list as $l) {
        $res[] = array(
            'id' => $l->id,
            'name' => $l->name
        );
    }
    return response()->json($res);
});

Route::prefix('student')->middleware('allow.student')->group(function () {
    //SRTP
    Route::post('srtp', 'SrtpController@create');                           //创建SRTP项目
    Route::get('srtp', 'SrtpController@getMySrtp');                         //获取自己的SRTP项目
    Route::patch('srtp/update', 'SrtpController@updateMySrtp');             //更新自己的SRTP项目状态
    //毕业
    Route::post('graduation', 'GraduationController@create');               //创建毕业项目
    Route::get('graduation', 'GraduationController@getMine');               //获取自己的毕业项目
    Route::patch('graduation/update', 'GraduationController@updateMine');   //更新自己的毕业项目状态
});

Route::prefix('teacher')->middleware('allow.teacher')->group(function () {
    //教改
    Route::post('teach', 'TeachController@create');                             //创建一条教改项目
    Route::get('teach', 'TeachController@getMyAll');                            //获取自己所有教改项目列表
    Route::get('teach/{id}', 'TeachController@getMyOne');                       //获取自己单条教改项目详细信息
    Route::patch('teach/{id}/update', 'TeachController@updateMyOne');           //更新一条教改项目状态
    //毕业
    Route::get('graduation', 'GraduationController@getMyAll');                  //获取自己指导的所有毕业项目
    Route::patch('graduation/{id}/update', 'GraduationController@updateOne');   //更新单条毕业项目状态
});

Route::prefix('assistant')->middleware('allow.assistant')->group(function () {
    //SRTP
    Route::get('srtp', 'SrtpController@all');                               //获取所有SRTP项目
    Route::patch('srtp/{id}/update', 'SrtpController@updateSrtp');          //更新单条SRTP项目状态
    //教改
    Route::get('teach', 'TeachController@getAll');                          //获取所有教改项目
    Route::patch('teach/{id}/update', 'TeachController@updateOne');         //更新单条教改项目
});