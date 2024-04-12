<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeskController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\WilayaController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResponderController;
use App\Http\Controllers\RemarketingController;
use App\Http\Controllers\FacebookPageController;
use App\Http\Controllers\ConversationsController;
use App\Http\Controllers\MessagesTemplatesController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\RemarketingCategoryController;
use App\Http\Controllers\RemarketingIntervalController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::middleware(['auth', 'access_token'])->prefix('dashboard')->group(function () {
    Route::middleware('permission:dashboard_consult')->controller(DashboardController::class)->group(function(){
        Route::get('', "index")->name('dashboard');
    });
    Route::middleware('permission:desks_consult')->prefix("desks")->controller(DeskController::class)->group(function(){
        Route::get('', "index")->name('desks');
        Route::post('', "store")->middleware('permission:desks_create')->name('desks_create');
        Route::put('{desk}/edit', "update")->middleware('permission:desks_edit')->name('desks_edit');
        Route::delete('{desk}/delete', "destroy")->middleware('permission:desks_delete')->name('desks_delete');
    });
    Route::middleware('permission:wilayas_consult')->prefix("wilayas")->controller(WilayaController::class)->group(function(){
        Route::get('', "index")->name('wilayas');
        Route::put('edit', "update")->middleware('permission:wilayas_edit')->name('wilayas_edit');
        Route::get('edit/auto', "auto_update")->middleware('permission:wilayas_edit')->name('wilayas_auto_edit');
    });
    Route::middleware('permission:products_consult')->prefix("products")->controller(ProductController::class)->group(function(){
        Route::get('', "index")->name('products');
        Route::post('', "store")->middleware('permission:products_create')->name('products_create');
        Route::put('{product}/edit', "update")->middleware('permission:products_edit')->name('products_edit');
        Route::delete('{product}/delete', "destroy")->middleware('permission:products_delete')->name('products_delete');
    });
    Route::middleware('permission:conversations_consult')->prefix("conversations")->controller(ConversationsController::class)->group(function(){
        Route::get('', "index")->name('conversations');
        Route::get('page/{page}', "conversations")->name('conversations_page');
        Route::post('{conversation}', "sendmessage")->name('conversations_sendmessage');
        Route::get('{conversation}', "conversation")->name('conversations_conversation');
    });
    Route::middleware('permission:orders_restricted_consult')->prefix("orders")->controller(OrderController::class)->group(function(){
        Route::get('create', "create")->name('orders_create')->middleware('permission:orders_create');
        Route::post('create', "store")->middleware('permission:orders_create');
        Route::get('create/p/{product}', "create_from_product")->name('orders_create_product')->middleware('permission:orders_create');
        Route::get('create/c/{conversation}', "create_from_conversation")->name('orders_create_conversation')->middleware('permission:orders_create');
        Route::get('{total}/totext', function ($total){
            $conversations = DB::select("Select * 
            FROM facebook_conversations
            WHERE `facebook_conversation_id` IN(
                SELECT conversation
                FROM facebook_messages
                WHERE `message` REGEXP '^(0[5-7]|[5-7])[0-9]{8}'
                AND `sented_from` = 'user'
                GROUP BY conversation
            )
            limit $total
            ");
            foreach($conversations as $conversation){
                $messages = DB::select("Select * 
                FROM facebook_messages
                WHERE `conversation` = '".$conversation->facebook_conversation_id."'
                ORDER BY created_at
                ");
                echo '<div dir="rtl">';
                echo '<center><h2>المحادثة '.$conversation->facebook_conversation_id.'</h2></center>';
                foreach($messages as $message){
                    echo '<b>'.($message->sented_from=='user'?'الزبون':'المتجر').'</b>: '.$message->message."<br>";
                }
                echo '</div>';
            }
        });
        Route::get('{wilaya}/getDelivery', "getDelivery");
        Route::get('{wilaya}/getCommunes', "getCommunes");
        Route::get('pending', "pending")->name('orders_pending');
        Route::get('towilaya', "towilaya")->name('orders_towilaya');
        Route::get('delivery', "delivery")->name('orders_delivery');
        Route::get('delivered', "delivered")->name('orders_delivered');
        Route::get('back', "back")->name('orders_back');
        Route::get('archived', "archived")->name('orders_archived');
    });
    
    Route::middleware('permission:messagestemplates_consult')->prefix("templates")->controller(MessagesTemplatesController::class)->group(function(){
        Route::get('', "index")->name('messagestemplates');
        Route::post('', "store")->middleware('permission:users_create')->name('messagestemplates_create');
        Route::put('{template}/edit', "update")->middleware('permission:messagestemplates_edit')->name('messagestemplates_edit');
        Route::delete('{template}/delete', "destroy")->middleware('permission:messagestemplates_delete')->name('messagestemplates_delete');
    });
    Route::middleware('permission:users_consult')->prefix("users")->controller(UserController::class)->group(function(){
        Route::get('', "index")->name('users');
        Route::post('', "store")->middleware('permission:users_create')->name('users_create');
        Route::put('{user}/edit', "update")->middleware('permission:users_edit')->name('users_edit');
        Route::delete('{user}/delete', "destroy")->middleware('permission:users_delete')->name('users_delete');
    });
    Route::middleware('permission:stock_consult')->prefix("stock")->controller(StockController::class)->group(function(){
        Route::get('', "index")->name('stock');
        Route::post('', "store")->middleware('permission:stock_create')->name('stock_create');
        Route::put('{stock}/edit', "update")->middleware('permission:stock_edit')->name('stock_edit');
        Route::delete('{stock}/delete', "destroy")->middleware('permission:stock_delete')->name('stock_delete');
    });
    Route::middleware('permission:remarketing_categories_consult')->prefix("remarketing/categories")->controller(RemarketingCategoryController::class)->group(function(){
        Route::get('', "index")->name('remarketing_categories');
        Route::post('create', "store")->middleware('permission:remarketing_categories_create')->name('remarketing_categories_create');
        Route::put('{category}/edit', "update")->middleware('permission:remarketing_categories_edit')->name('remarketing_categories_edit');
        Route::delete('{category}/delete', "destroy")->middleware('permission:remarketing_categories_delete')->name('remarketing_categories_delete');
    });
    Route::middleware('permission:remarketing_consult')->prefix("remarketing/timeout")->controller(RemarketingController::class)->group(function(){
        Route::get('', "index")->name('remarketing');
        Route::get('{remarketing}/history', "history")->name('remarketing_history');
        Route::get('categories/{category}', "category")->name('remarketing_category');
        Route::get('categories/{category}/elements', "sub_category")->name('remarketing_sub_category');
        Route::get('create', "create")->middleware('permission:remarketing_create')->name('remarketing_create');
        Route::post('create', "store")->middleware('permission:remarketing_create');
        Route::get('{remarketing}/activate', "activate")->middleware('permission:remarketing_edit')->name('remarketing_activate');
        Route::put('{remarketing}/activate', "activate_store")->middleware('permission:remarketing_edit');
        Route::put('{remarketing}/deactivate', "deactivate_store")->middleware('permission:remarketing_edit')->name('remarketing_deactivate');
        Route::get('{remarketing}/edit', "edit")->middleware('permission:remarketing_edit')->name('remarketing_edit');
        Route::put('{remarketing}/edit', "update")->middleware('permission:remarketing_edit');
        Route::delete('{remarketing}/delete', "destroy")->middleware('permission:remarketing_delete')->name('remarketing_delete');
    });
    Route::middleware('permission:remarketing_interval_consult')->prefix("remarketing/interval")->controller(RemarketingIntervalController::class)->group(function(){
        Route::get('', "index")->name('remarketing_interval');
        Route::get('{remarketing}/history', "history")->name('remarketing_interval_history');
        Route::get('categories/{category}', "category")->name('remarketing_interval_category');
        Route::get('categories/{category}/elements', "sub_category")->name('remarketing_interval_sub_category');
        Route::get('create', "create")->middleware('permission:remarketing_interval_create')->name('remarketing_interval_create');
        Route::post('create', "store")->middleware('permission:remarketing_interval_create');
        Route::get('{remarketing}/activate', "activate")->middleware('permission:remarketing_interval_edit')->name('remarketing_interval_activate');
        Route::put('{remarketing}/activate', "activate_store")->middleware('permission:remarketing_interval_edit');
        Route::put('{remarketing}/deactivate', "deactivate_store")->middleware('permission:remarketing_interval_edit')->name('remarketing_interval_deactivate');
        Route::get('{remarketing}/edit', "edit")->middleware('permission:remarketing_interval_edit')->name('remarketing_interval_edit');
        Route::put('{remarketing}/edit', "update")->middleware('permission:remarketing_interval_edit');
        Route::delete('{remarketing}/delete', "destroy")->middleware('permission:remarketing_interval_delete')->name('remarketing_interval_delete');
    });
    Route::middleware('permission:tracking_consult')->prefix("tracking")->controller(TrackingController::class)->group(function(){
        Route::get('', "index")->name('tracking');
        Route::post('edit', "edit")->middleware('permission:tracking_edit')->name('tracking_edit');
    });
    Route::middleware('permission:responder_consult')->prefix("responder")->controller(ResponderController::class)->group(function(){
        Route::get('', "index")->name('responder');
        Route::post('edit', "edit")->middleware('permission:responder_edit')->name('responder_edit');
    });
    Route::middleware('permission:settings_consult')->prefix("settings")->controller(SettingsController::class)->group(function(){
        Route::get('', "index")->name('settings');
        Route::post('edit', "edit")->name('settings_edit');
    });

    Route::middleware('permission:facebook_reconnect')->controller(FacebookPageController::class)->group(function(){
        Route::get('oauth/conversations/load', 'load_conversations')->name('facebook_load_conversations');
        Route::get('oauth/facebook', 'redirectToFacebook')->name('facebook_reconnect');
        Route::get('oauth/facebook/callback', 'handleFacebookCallback')->withoutMiddleware('access_token');
        Route::get('oauth/facebook/logout', 'logout');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/documentation', [PageController::class, 'documentation'])->name('documentation');
});

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::controller(PageController::class)->group(function (){
    Route::get('', 'index');
});