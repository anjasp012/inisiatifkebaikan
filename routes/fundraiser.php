<?php

use Illuminate\Support\Facades\Route;

// Fundraiser Registration
Route::livewire('/fundraiser/daftar', 'pages::fundraiser.⚡daftar')->name('fundraiser.daftar');

// Fundraiser Dashboard
Route::livewire('/fundraiser/dashboard', 'pages::fundraiser.⚡dashboard')->middleware('auth')->name('fundraiser.dashboard');

// Fundraiser Galang Dana List
Route::livewire('/fundraiser/galang-dana', 'pages::fundraiser.galang-dana.⚡index')->middleware('auth')->name('fundraiser.galang-dana.index');

// Fundraiser Campaign Management
Route::livewire('/fundraiser/galang-dana/buat', 'pages::fundraiser.galang-dana.buat')->middleware('auth')->name('fundraiser.campaign.buat'); // Keeping name for compatibility or update? I'll update name to be consistent but usually route names are used in views.
// Let's check if I should update route names. The view I just wrote uses 'fundraiser.galang-dana.kelola' and 'fundraiser.campaign.buat'.
// I will keep 'fundraiser.campaign.buat' and 'fundraiser.campaign.ubah' for now to match my view, or update view. I should update view to use 'fundraiser.galang-dana.*' for consistency.
// Actually, in the view I wrote: route('fundraiser.campaign.buat') and route('fundraiser.galang-dana.kelola').
// So I need:
// 1. fundraiser.galang-dana.index
// 2. fundraiser.campaign.buat (or update view)
// 3. fundraiser.galang-dana.kelola

Route::livewire('/fundraiser/galang-dana/buat', 'pages::fundraiser.galang-dana.⚡buat')->middleware('auth')->name('fundraiser.campaign.buat');
Route::livewire('/fundraiser/galang-dana/{campaign:slug}/ubah', 'pages::fundraiser.galang-dana.⚡ubah')->middleware('auth')->name('fundraiser.campaign.ubah');
Route::livewire('/fundraiser/galang-dana/{campaign:slug}/kelola', 'pages::fundraiser.galang-dana.⚡kelola')->middleware('auth')->name('fundraiser.galang-dana.kelola');

Route::livewire('/fundraiser/galang-dana/{campaign:slug}/kabar', 'pages::fundraiser.galang-dana.kabar.⚡index')->middleware('auth')->name('fundraiser.galang-dana.kabar');
Route::livewire('/fundraiser/galang-dana/{campaign:slug}/donatur', 'pages::fundraiser.galang-dana.donatur.⚡index')->middleware('auth')->name('fundraiser.galang-dana.donatur');
Route::livewire('/fundraiser/galang-dana/{campaign:slug}/pencairan', 'pages::fundraiser.galang-dana.pencairan.⚡index')->middleware('auth')->name('fundraiser.galang-dana.pencairan');
