<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/properties', function () {
    return view('Properties.index');
});

Route::get('/properties/{id}', function ($id) {
    return view('Properties.show');
});

// In routes/web.php
Route::get('/landlord/dashboard', function () {
    return view('landlord.dashboard');
}); 

Route::get('/tenant/dashboard', function () {
    return view('tenant.dashboard');
}); 