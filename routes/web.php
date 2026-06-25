<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'entries')->name('home');
Route::livewire('/new', 'edit-entry')->name('new');
Route::livewire('/{entry}', 'edit-entry')->name('edit');
