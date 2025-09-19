<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\SuperAdmin;

Route::get('/SuperAdmin-dashboard', [SuperAdmin::class, 'dashboard'])->name('api.dashboard');


