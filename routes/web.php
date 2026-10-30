<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/tournaments/create', 'tournaments.create')->name('tournaments.create');
