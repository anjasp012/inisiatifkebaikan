<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::livewire('/', 'pages::public.home')->name('home');
Route::livewire('/kategori', 'pages::public.category.index')->name('category.index');
Route::livewire('/campaign/kategori/{category:slug}', 'pages::public.campaign.index')->name('campaign.index');
Route::livewire('/campaign/{campaign:slug}', 'pages::public.campaign.show')->name('campaign.show');

// Donation Flow
Route::livewire('/donasi/{campaign:slug}/nominal', 'pages::public.donation.amount')->name('donation.amount');
Route::livewire('/donasi/{campaign:slug}/data', 'pages::public.donation.data')->name('donation.data');
Route::livewire('/donasi/{campaign:slug}/pembayaran', 'pages::public.donation.payment')->name('donation.payment');
Route::livewire('/donasi/instruksi/{transaction_id}', 'pages::public.donation.instruction')->name('donation.instruction');
Route::livewire('/donasi-saya', 'pages::public.donasi-saya.index')->name('donasi-saya');

Route::livewire('/login', 'pages::auth.login')->name('login');
Route::livewire('/register', 'pages::auth.register')->name('register');
Route::livewire('/verifikasi', 'pages::auth.verification')->name('verification');



include 'admin.php';
include 'fundraiser.php';

Route::get('/storage-link', function () {
    $exitCode = Artisan::call('storage:link');
    return 'Storage link has been created. Exit code: ' . $exitCode;
});
