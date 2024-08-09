<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeskController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\WilayaController;
use App\Http\Controllers\FundingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\InvoicerController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResponderController;
use App\Http\Controllers\BotsEngineController;
use App\Http\Controllers\GroupJoinerController;
use App\Http\Controllers\RemarketingController;
use App\Http\Controllers\DeliveryMensController;
use App\Http\Controllers\FacebookPageController;
use App\Http\Controllers\ConversationsController;
use App\Http\Controllers\FacebookAccountController;
use App\Http\Controllers\MessagesTemplatesController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\RemarketingCategoryController;
use App\Http\Controllers\RemarketingIntervalController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::middleware(['auth', 'access_token'])->group(function () {
    Route::middleware('permission:dashboard_consult')->controller(DashboardController::class)->group(function(){
        Route::get('', "index")->name('dashboard');
    });
    Route::middleware('permission:desks_consult')->prefix("desks")->controller(DeskController::class)->group(function(){
        Route::get('', "index")->name('desks');
        Route::post('', "store")->middleware('permission:desks_create')->name('desks_create');
        Route::put('{desk}/edit', "update")->middleware('permission:desks_edit')->name('desks_edit');
        Route::delete('{desk}/delete', "destroy")->middleware('permission:desks_delete')->name('desks_delete');
    });
    Route::middleware('permission:deliverymens_consult')->prefix("delivery-mens")->controller(DeliveryMensController::class)->group(function(){
        Route::get('', "index")->name('deliverymens');
        Route::get('{wilaya}', "wilaya")->name('deliverymens_edit');
        Route::post('{wilaya}', "edit_wilaya");

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

        Route::get('{order}/addtoecotrack', "addtoecotrack")->name('orders_addtoecotrack');

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

        Route::get('stock/{product}/get', "getProductStock");
        Route::get('stock-desk/{product}/{desk}/get', "getProductDeskStock");

        
        Route::get('import', "import")->name('orders_import');
        Route::post('import', "importpost");
        Route::put('import', "importsave");

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

        Route::get('categories/{category}/deactivate', "category_deactivate")->middleware('permission:remarketing_edit')->name('remarketing_subcategory_deactivate');
        Route::get('categories/{category}/activate', "category_activate")->middleware('permission:remarketing_edit')->name('remarketing_subcategory_activate');


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
        Route::put('{responder}/activate', "activate")->middleware('permission:responder_edit')->name('responder_activate');
        Route::put('{responder}/deactivate', "deactivate")->middleware('permission:responder_edit')->name('responder_deactivate');
        Route::get('{responder}/history', "history")->name('responder_history');
        Route::get('create', "create")->middleware('permission:responder_create')->name('responder_create');
        Route::post('create', "store")->middleware('permission:responder_create');
        Route::get('{responder}/edit', "edit")->middleware('permission:responder_edit')->name('responder_edit');
        Route::put('{responder}/edit', "update")->middleware('permission:responder_edit');
        Route::delete('{responder}/delete', "destroy")->middleware('permission:responder_delete')->name('responder_delete');
    });
    Route::middleware('permission:invoicer_consult')->prefix("invoicer")->controller(InvoicerController::class)->group(function(){
        Route::post('products/store', "products_store")->middleware('permission:invoicer_create_product')->name('invoicer_products_create');
        Route::post('products/store/all', "products_store_all")->middleware('permission:invoicer_upload')->name('invoicer_products_create_all');
        Route::put('products/{product}/edit', "products_update")->middleware('permission:invoicer_edit_product')->name('invoicer_products_edit');
        Route::delete('products/{product}/delete', "products_destroy")->middleware('permission:invoicer_delete_product')->name('invoicer_products_delete');
        
        Route::get('', "index")->name('invoicer');
        Route::get('{invoice}', "invoice")->name('invoicer_invoice');
        Route::put('{invoice}/edit', "update")->name('invoicer_edit');
        Route::post('upload', "upload")->middleware('permission:invoicer_upload')->name('invoicer_upload');
    });
    Route::prefix('accounting')->name("accounting")->group(function(){
        Route::middleware('permission:accounting_investors_consult')->prefix("investors")->controller(InvestorController::class)->group(function(){
            Route::get('', "index")->name('investors');
            Route::get('create', "create")->middleware('permission:accounting_investors_create')->name('investors_create');
            Route::post('create', "store")->middleware('permission:accounting_investors_create');
            Route::get('{investor}/edit', "edit")->middleware('permission:accounting_investors_edit')->name('investors_edit');
            Route::put('{investor}/edit', "update")->middleware('permission:accounting_investors_edit');
            Route::delete('{investor}/delete', "destroy")->middleware('permission:accounting_investors_delete')->name('investors_delete');
        });
        Route::middleware('permission:accounting_fundings_consult')->prefix("investors/{investor}/fundings")->controller(FundingController::class)->group(function(){
            Route::get('', "index")->name('fundings');
            Route::get('create', "create")->middleware('permission:accounting_fundings_create')->name('fundings_create');
            Route::post('create', "store")->middleware('permission:accounting_fundings_create');
            Route::get('{funding}/edit', "edit")->middleware('permission:accounting_fundings_edit')->name('fundings_edit');
            Route::put('{funding}/edit', "update")->middleware('permission:accounting_fundings_edit');
            Route::delete('{funding}/delete', "destroy")->middleware('permission:accounting_fundings_delete')->name('fundings_delete');
        });
        Route::middleware('permission:accounting_purchases_consult')->prefix("purchases")->controller(PurchaseController::class)->group(function(){
            Route::get('', "index")->name('purchases');
            Route::get('create', "create")->middleware('permission:accounting_purchases_create')->name('purchases_create');
            Route::post('create', "store")->middleware('permission:accounting_purchases_create');
            Route::get('{purchase}/edit', "edit")->middleware('permission:accounting_purchases_edit')->name('purchases_edit');
            Route::put('{purchase}/edit', "update")->middleware('permission:accounting_purchases_edit');
            Route::delete('{purchase}/delete', "destroy")->middleware('permission:accounting_purchases_delete')->name('purchases_delete');
        });
        Route::middleware('permission:accounting_sales_consult')->prefix("sales")->controller(PurchaseController::class)->group(function(){
            Route::get('', "index")->name('sales');
            Route::get('create', "create")->middleware('permission:accounting_sales_create')->name('sales_create');
            Route::post('create', "store")->middleware('permission:accounting_sales_create');
            Route::get('{sale}/edit', "edit")->middleware('permission:accounting_sales_edit')->name('sales_edit');
            Route::put('{sale}/edit', "update")->middleware('permission:accounting_sales_edit');
            Route::delete('{sale}/delete', "destroy")->middleware('permission:accounting_sales_delete')->name('sales_delete');
        });
    
    });
    Route::prefix('bots')->group(function(){
        Route::middleware('permission:accounts_consult')->prefix("accounts")->controller(FacebookAccountController::class)->group(function(){
            Route::get('', "index")->name('accounts');
            Route::post('create', "store")->middleware('permission:accounts_create')->name('accounts_create');
            Route::put('{account}/edit', "update")->middleware('permission:accounts_edit')->name('accounts_edit');
            Route::delete('{account}/delete', "destroy")->middleware('permission:accounts_delete')->name('accounts_delete');

            Route::get('categories/{category}', "category")->middleware('permission:accounts_create')->name('accounts_category');
            Route::post('categories/create', "store_category")->middleware('permission:accounts_create')->name('accounts_category_create');
            Route::put('categories/{category}/edit', "update_category")->middleware('permission:accounts_create')->name('accounts_category_edit');
            Route::delete('categories/{category}/delete', "destroy_category")->middleware('permission:accounts_create')->name('accounts_category_delete');
        });
        Route::middleware('permission:botsengine_consult')->prefix("engine")->controller(BotsEngineController::class)->group(function(){
            Route::get('', "botsengine")->name('botsengine');
        });
        Route::prefix('FGMT')->group(function(){
            Route::middleware('permission:group_joiner_consult')->prefix("joiner")->controller(GroupJoinerController::class)->group(function(){
                Route::get('', "index")->name('group_joiner');
                Route::get('create', "create")->middleware('permission:group_joiner_create')->name('group_joiner_create');
                Route::post('create', "store")->middleware('permission:group_joiner_create');
                Route::get('{joiner}/history', "history")->middleware('permission:group_joiner_history')->name('group_joiner_history');
                Route::get('{joiner}/edit', "edit")->middleware('permission:group_joiner_edit')->name('group_joiner_edit');
                Route::put('{joiner}/edit', "update")->middleware('permission:group_joiner_edit');
                Route::delete('{joiner}/delete', "destroy")->middleware('permission:group_joiner_delete')->name('group_joiner_delete');
            });
        
            Route::middleware('permission:group_poster_consult')->prefix("poster")->controller(FacebookAccountController::class)->group(function(){
                Route::get('', "index")->name('group_poster');
                Route::get('create', "create")->middleware('permission:group_poster_create')->name('group_poster_create');
                Route::post('create', "store")->middleware('permission:group_poster_create');
                Route::get('{poster}/edit', "edit")->middleware('permission:group_poster_edit')->name('group_poster_edit');
                Route::put('{poster}/edit', "update")->middleware('permission:group_poster_edit');
                Route::delete('{poster}/delete', "destroy")->middleware('permission:group_poster_delete')->name('group_poster_delete');
            });
        });
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