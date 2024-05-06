<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\NotifController;
use App\Http\Controllers\Api\PaymentController;

use App\Http\Controllers\Api\TicketingController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\HistoryController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('login', [LoginController::class, 'login']);
Route::get('slider', [SliderController::class, 'get_data_banner']);
Route::post('password_update', [UserController::class, 'password_update']);
Route::get('user_data/{id}', [UserController::class, 'user_data']);
Route::post('profile_update', [UserController::class, 'profile_update'] );
Route::post('profile_upload', [UserController::class, 'upload']);
Route::get('notif_list/{id}', [NotifController::class, 'notif_list']);
Route::get('payment_list/{id}', [PaymentController::class, 'payment_list']);
Route::post('payment_post', [PaymentController::class, 'payment_post']);
Route::get('kwitansi/{id}/{user_id}', [PaymentController::class, 'kwitansi']);
Route::get('ticketing_list/{id}', [TicketingController::class, 'ticketing_list']);
Route::get('ticketing_detail/{number}', [TicketingController::class, 'ticketing_detail']);
Route::get('department', [TicketingController::class, 'department']);
Route::post('open', [TicketingController::class, 'open']);
Route::post('ticketing_upload', [TicketingController::class, 'upload']);
Route::post('reply_upload', [TicketingController::class, 'reply_upload']);
Route::post('reply', [TicketingController::class, 'reply']);
Route::get('booking_list', [BookingController::class, 'booking_list']);
Route::post('booking_resume', [BookingController::class, 'booking_resume']);
Route::post('booking_invoice', [BookingController::class, 'booking_invoice']);
Route::post('count_booking_price', [BookingController::class, 'count_booking_price']);
Route::get('term', [BookingController::class, 'term']);
Route::post('transaction', [BookingController::class, 'transaction']);
Route::post('payment_process', [BookingController::class, 'payment_process']);
Route::get('print_ticket/{id}', [BookingController::class, 'print_ticket']);
Route::get('history/{id}', [HistoryController::class, 'history']);
Route::post('update_fcm_token', [LoginController::class, 'update_fcm_token']);
Route::get('mobile_redirect/{text}', [UserController::class, 'mobile_redirect']);
Route::post('booking_check', [BookingController::class, 'booking_check']);