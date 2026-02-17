<?php

use Illuminate\Support\Facades\Route;

// Fundraiser Registration
Route::livewire('/fundraiser/daftar', 'pages::fundraiser.daftar')->name('fundraiser.daftar');

// Fundraiser Dashboard (requires auth & approved fundraiser)
Route::livewire('/fundraiser/dashboard', 'pages::fundraiser.dashboard')->middleware('auth')->name('fundraiser.dashboard');

// Fundraiser Campaign Management
Route::livewire('/fundraiser/campaign', 'pages::fundraiser.campaign.index')->middleware('auth')->name('fundraiser.campaign');
Route::livewire('/fundraiser/campaign/buat', 'pages::fundraiser.campaign.buat')->middleware('auth')->name('fundraiser.campaign.buat');
Route::livewire('/fundraiser/campaign/ubah/{campaign:slug}', 'pages::fundraiser.campaign.ubah')->middleware('auth')->name('fundraiser.campaign.ubah');
