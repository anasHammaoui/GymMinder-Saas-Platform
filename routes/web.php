<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get("/register",function(){
    return view("auth.signUp");
}) -> name("register");
Route::get("/login",function(){
    return view("auth.login");
}) -> name("login");
Route::get("/forgetpassword",function(){
    return view("auth.forgetPassword");
}) -> name("forget");
Route::get("/resetpassword",function(){
    return view("auth.resetPassword");
}) -> name("reset");