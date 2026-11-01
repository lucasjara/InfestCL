<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/tournaments/create', 'tournaments.create')->name('tournaments.create');
Route::view('/tournaments/bracket', 'tournaments.bracket')->name('tournaments.bracket');
Route::view('/cards/list', 'cards.list')->name('cards.list');
Route::view('/album', 'album.index')->name('album.index');
Route::view('/album/{id}', 'album.show')->name('album.show');
