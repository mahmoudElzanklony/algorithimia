<?php



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\auth\AuthControllerApi;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Api\UsersControllerApi;
use App\Http\Controllers\classes\general\GeneralServiceController;
use App\Http\Controllers\CitiesControllerResource;
use App\Http\Controllers\CountriesControllerResource;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\AdsController;
use App\Http\Controllers\FAQController;



Route::group(['middleware'=>'changeLang'],function (){
    Route::get('/test',[AuthControllerApi::class,'test']);


    Route::group(['prefix'=>'/auth'],function(){
        Route::post('/register',[AuthControllerApi::class,'register_post']);
    });
    Route::post('/login',[AuthControllerApi::class,'login_api']);
    Route::post('/logout',[AuthControllerApi::class,'logout_api']);

    Route::group(['prefix'=>'/categories'],function(){
        Route::get('/',[CategoriesController::class,'index']);
        Route::get('/{id}',[CategoriesController::class,'show']);
    });
    Route::group(['prefix'=>'/services'],function(){
        Route::get('/',[ServicesController::class,'index']);
    });
    Route::group(['prefix'=>'/projects'],function(){
        Route::get('/',[ProjectsController::class,'index']);
    });
    Route::group(['prefix'=>'/ads'],function(){
        Route::get('/',[AdsController::class,'index']);
    });
    Route::group(['prefix'=>'/faqs'],function(){
        Route::get('/',[FAQController::class,'index']);
    });




    Route::post('/validate-user',[AuthControllerApi::class,'validate_user']);
    Route::get('/user',[AuthControllerApi::class,'user'])->middleware('CheckApiAuth');


    //----------------------- start of dashboard------------------
    Route::group(['prefix'=>'/dashboard','middleware'=>'CheckApiAuth'],function(){
        Route::post('/users',[DashboardController::class,'users']);
        Route::post('/categories',[DashboardController::class,'categories']);
        Route::post('/services',[DashboardController::class,'services']);
        Route::post('/projects',[DashboardController::class,'projects']);
        Route::post('/posts',[DashboardController::class,'posts']);
        Route::post('/faqs',[DashboardController::class,'faqs']);
        Route::post('/categories/save',[CategoriesController::class,'save']);
        Route::post('/services/save',[ServicesController::class,'save']);
        Route::post('/projects/save',[ProjectsController::class,'save']);
        Route::post('/posts/save',[AdsController::class,'save']);
        Route::post('/faqs/save',[FAQController::class,'save']);
        Route::group(['prefix'=>'/languages'],function(){
            Route::get('/',[DashboardController::class,'all_languages']);
            Route::post('/save',[DashboardController::class,'save_lang']);
        });
        Route::group(['prefix'=>'/support'],function(){
            Route::get('/',[DashboardController::class,'all_support']);
            Route::post('/save',[DashboardController::class,'save_support']);
        });


        Route::group(['prefix'=>'/countries'],function(){
            Route::post('/',[DashboardController::class,'countries']);
            Route::post('/save',[DashboardController::class,'save_countries']);
        });
    });
       //----------------------- end of dashboard------------------



    // delete item
    Route::post('/delete-item',[GeneralServiceController::class,'delete_item']);



    Route::resources([
        'countries'=>CountriesControllerResource::class,
        'cities'=>CitiesControllerResource::class,
    ]);





});
