<?php

use App\Http\Controllers\Admins\AdminsController;
use App\Http\Controllers\Admins\AuthController;
use App\Http\Controllers\Admins\BannerIklanController;
use App\Http\Controllers\Admins\DashboardController;
use App\Http\Controllers\Admins\PenyeliaController;
use App\Http\Controllers\Admins\PenyeliaKategoriController;
use App\Http\Controllers\Admins\UnitBisnisController;
use App\Http\Controllers\Main\AuthUsersController;
use App\Http\Controllers\Main\DashboardMainController;
use App\Http\Controllers\Main\TicketingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/',[DashboardMainController::class, 'index'])->name("home_public");
// Route::get('register-user',[AuthUsersController::class, 'index_register'])->name("register_user");
// Route::get('auth-user',[AuthUsersController::class, 'index_login'])->name('login_user');
Route::get('/dashboard', [DashboardMainController::class, 'dashboard']);
Route::get('/term', [DashboardMainController::class, 'term']);
Route::get('/privacy', [DashboardMainController::class,'privacy']);
Route::get('/contact', [DashboardMainController::class, 'contact']);
Route::get('/sendwa', [DashboardMainController::class, 'send_wa']);
Route::get('/frontend_register', [AuthUsersController::class, 'register']);
Route::post('/register_now', [AuthUsersController::class, 'register_now'])->name('register_now');
Route::get('/otp', [AuthUsersController::class, 'otp']);
Route::post('/send_otp', [AuthUsersController::class, 'send_otp'])->name('send.otp');
Route::get('/login', [AuthUsersController::class, 'login']);
Route::post('/frontend_login', [AuthUsersController::class, 'frontend_login'])->name('frontend.login');
Route::get('/frontend_logout', [AuthUsersController::class, 'logout']);
Route::get('/frontend_dashboard', [AuthUsersController::class, 'dashboard']); 
Route::get('/frontend_booking', [AuthUsersController::class, 'booking']);
Route::get('/booking_detail/{slug}', [AuthUsersController::class, 'booking_detail']);
Route::get('/display_calendar/{bulan}/{tahun}', [AuthUsersController::class, 'display_calendar']);
Route::post('/booking_time', [AuthUsersController::class, 'booking_time']);
Route::post('/transaction', [AuthUsersController::class, 'transaction']);
Route::get('/riwayat', [AuthUsersController::class, 'riwayat']);
Route::post('/payment_process', [AuthUsersController::class, 'payment_process']);
Route::post('/xendit_callback', [AuthUsersController::class, 'callback']);
Route::get('/print_ticket/{id}', [AuthUsersController::class, 'print']);
Route::get('/ticketing', [TicketingController::class, 'index'] );
Route::get('/ticketing_add', [TicketingController::class, 'add']);
Route::post('/open_ticket', [TicketingController::class, 'open'])->name('open.ticket');
Route::get('/ticketing_detail/{number}', [TicketingController::class, 'ticketing_detail']);
Route::get('/donwload_ticketing/{file}', [TicketingController::class, 'download']);
Route::post('/reply_ticket', [TicketingController::class, 'reply'])->name('reply.ticket');

Route::get('/update_iuran', function(){

   $array = [
   
   ];


});

// for admins

Route::get('/login-admins', [AuthController::class, 'index'])->name("login_admin");

Route::middleware(['throttle:webAuthAdmin'])->group(function(){
   Route::post('/process-auth-admin',[AuthController::class, 'prosesAuth']);
});

Route::prefix("backdata")
->middleware('authAdmins')
->group(function() {
   Route::get('/',[DashboardController::class, 'index'])->name("home_admin");

   Route::get('/logout',[AuthController::class, 'logout'])->name("logout_admin");

   // admin crud register
   Route::post("admins-list",[AdminsController::class,'ajax_list']) ;
   Route::post("admins-list-trash",[AdminsController::class,'ajax_list_trash']) ;
   Route::get("admins/{id}/trash",[AdminsController::class,'editTrash']) ;
   Route::post("admins/{id}/restore",[AdminsController::class,'restore']) ;
   Route::resource("admins",AdminsController::class);

   // kategori penyelia crud data
   Route::post("penyelia-kategori-list",[PenyeliaKategoriController::class,'ajax_list']) ;
   Route::post("penyelia-kategori-list-trash",[PenyeliaKategoriController::class,'ajax_list_trash']) ;
   Route::get("penyelia-kategori/{id}/trash",[PenyeliaKategoriController::class,'editTrash']) ;
   Route::post("penyelia-kategori/{id}/restore",[PenyeliaKategoriController::class,'restore']) ;
   Route::resource("penyelia-kategori",PenyeliaKategoriController::class);

   //penyelia crud data
   Route::post("penyelia-list",[PenyeliaController::class,'ajax_list']) ;
   Route::post("penyelia-list-trash",[PenyeliaController::class,'ajax_list_trash']) ;
   Route::get("penyelia/{id}/trash",[PenyeliaController::class,'editTrash']) ;
   Route::post("penyelia/{id}/restore",[PenyeliaController::class,'restore']) ;
   Route::resource("penyelia",PenyeliaController::class);

   // banner iklan
   Route::post("banner-iklan-list",[BannerIklanController::class,'ajax_list']) ;
   Route::post("banner-iklan-list-trash",[BannerIklanController::class,'ajax_list_trash']) ;
   Route::get("banner-iklan/{id}/trash",[BannerIklanController::class,'editTrash']) ;
   Route::post("banner-iklan/{id}/restore",[BannerIklanController::class,'restore']) ;
   Route::resource("banner-iklan",BannerIklanController::class);

   // unit bisnis
   Route::post("unit-bisnis-list",[UnitBisnisController::class,'ajax_list']) ;
   Route::post("unit-bisnis-list-trash",[UnitBisnisController::class,'ajax_list_trash']) ;
   Route::get("unit-bisnis/{id}/trash",[UnitBisnisController::class,'editTrash']) ;
   Route::post("unit-bisnis/{id}/restore",[UnitBisnisController::class,'restore']) ;
   Route::resource("unit-bisnis",UnitBisnisController::class);

});

Route::get("insert", function(){
   DB::table("admins")->where('id', 1)->update([
      "password" => Hash::make("123456")
   ]);
});


Route::group(['prefix' => 'filemanager', 'middleware' => ['authAdmins']], function () {
 
});