<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/login', 'loginControllerApi@login');
Route::post('/login', 'loginControllerApi@login');
Route::get('/logout', 'loginControllerApi@logout');
Route::get('/signup', 'loginControllerApi@signup');
Route::post('/signup', 'loginControllerApi@signup');
Route::post('/signup-patient', 'loginControllerApi@signup_patient');
Route::get('/signup-patient', 'loginControllerApi@signup_patient');
Route::get('/signup-doctor', 'loginControllerApi@signup_doctor');
Route::post('/signup-doctor', 'loginControllerApi@signup_doctor');
Route::post('/signup-hospital', 'loginControllerApi@signup_hospital');
Route::get('/signup-hospital', 'loginControllerApi@signup_hospital');
Route::get('/signup-pharmacy', 'loginControllerApi@signup_pharmacy');
Route::post('/signup-pharmacy', 'loginControllerApi@signup_pharmacy');


Route::get('/dashboard', 'dashboardControllerApi@dashboard');
Route::post('/dashboard', 'dashboardControllerApi@dashboard');
Route::post('/dashboard-doctor', 'dashboardDoctorControllerApi@dashboard');
Route::get('/dashboard-doctor', 'dashboardDoctorControllerApi@dashboard');
Route::post('/dashboard-hospital', 'dashboardHospitalControllerApi@dashboard');
Route::get('/dashboard-hospital', 'dashboardHospitalControllerApi@dashboard');
Route::get('/search-patients', 'dashboardDoctorControllerApi@search_patients');
Route::post('/search-patients', 'dashboardDoctorControllerApi@search_patients');
Route::get('/search-doctors', 'dashboardControllerApi@search_doctors');
Route::post('/search-doctors', 'dashboardControllerApi@search_doctors');
Route::get('/search-patients-h', 'dashboardHospitalControllerApi@search_patients');
Route::post('/search-patients-h', 'dashboardHospitalControllerApi@search_patients');
Route::get('/search-doctors-h', 'dashboardHospitalControllerApi@search_doctors');
Route::post('/search-doctors-h', 'dashboardHospitalControllerApi@search_doctors');
Route::get('/public-doctors', 'dashboardControllerApi@public_doctors');
Route::post('/public-doctors', 'dashboardControllerApi@public_doctors');
Route::post('/add-patients', 'dashboardDoctorControllerApi@add_patients');
Route::get('/add-patients', 'dashboardDoctorControllerApi@add_patients');
Route::post('/add-doctors', 'dashboardControllerApi@add_doctors');
Route::get('/add-doctors', 'dashboardControllerApi@add_doctors');
Route::post('/add-patients-h', 'dashboardHospitalControllerApi@add_patients');
Route::get('/add-patients-h', 'dashboardHospitalControllerApi@add_patients');
Route::post('/add-doctors-h', 'dashboardHospitalControllerApi@add_doctors');
Route::get('/add-doctors-h', 'dashboardHospitalControllerApi@add_doctors');
Route::get('/seen-notification', 'dashboardDoctorControllerApi@seen_notification');
Route::post('/seen-notification', 'dashboardDoctorControllerApi@seen_notification');
Route::get('/get-appointment-intervals', 'dashboardControllerApi@get_appointment_intervals');
Route::post('/get-appointment-intervals', 'dashboardControllerApi@get_appointment_intervals');
Route::post('/get-si-units', 'dashboardControllerApi@get_si_units');
Route::get('/get-si-units', 'dashboardControllerApi@get_si_units');
Route::get('/search-product', 'dashboardDoctorControllerApi@search_products');
Route::get('/check-compliance', 'dashboardDoctorControllerApi@check_compliance');

Route::get('/add-to-cart', 'dashboardControllerApi@addToCart');
Route::get('/remove-from-cart', 'dashboardControllerApi@removeFromCart');
Route::get('/update-cart', 'dashboardControllerApi@updateCart');
Route::get('/shopping-cart', 'dashboardControllerApi@displayCart');
Route::post('/shopping-cart', 'dashboardControllerApi@displayCart');
Route::get('/show-cart', 'dashboardControllerApi@showCart');
Route::get('/payconfirm', 'dashboardControllerApi@payconfirm');

// Pharmacy API endpoints
Route::post('/pharmacy/clearance', 'PharmacyApiController@storeClearance');
Route::post('/pharmacy/osr', 'PharmacyApiController@storeOutOfStockRequest');
Route::get('/pharmacy/partners', 'PharmacyApiController@partners');
Route::post('/pharmacy/message/send', 'PharmacyApiController@sendMessage');
Route::get('/pharmacy/messages/thread/{partnerId}/{partnerType}', 'PharmacyApiController@getThreadMessages');
Route::post('/pharmacy/reward/mark-paid', 'PharmacyApiController@markRewardPaid');
Route::post('/pharmacy/patient/vitals', 'PharmacyApiController@recordVitals');
Route::get('/pharmacy/patient/vitals-history/{id}', 'PharmacyApiController@getVitalsHistory');
