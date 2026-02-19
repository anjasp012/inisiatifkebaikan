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
Route::livewire('/donasi', 'pages::public.donation.index')->name('donation.index');

// Distribution Reports
Route::livewire('/laporan', 'pages::public.distribution.index')->name('distribution.index');
Route::livewire('/laporan/{distribution}', 'pages::public.distribution.show')->name('distribution.show');

// Articles
Route::livewire('/artikel', 'pages::public.article.index')->name('article.index');
Route::livewire('/artikel/{article:slug}', 'pages::public.article.show')->name('article.show');

// Account Routes
Route::middleware('auth')->group(function () {
    Route::livewire('/akun', 'pages::public.account.index')->name('account.index');
    Route::livewire('/akun/profil', 'pages::public.account.profile')->name('account.profile');
    Route::livewire('/akun/password', 'pages::public.account.password')->name('account.password');
});

Route::middleware('guest')->group(function () {
    Route::livewire('/login', 'pages::auth.login')->name('login');
    Route::livewire('/register', 'pages::auth.register')->name('register');
});

Route::livewire('/verifikasi', 'pages::auth.verification')->name('verification');

// Static Pages
Route::livewire('/tentang-kami', 'pages::public.about.index')->name('public.about');
Route::livewire('/syarat-ketentuan', 'pages::public.terms.index')->name('public.terms');
Route::livewire('/kebijakan-privasi', 'pages::public.privacy.index')->name('public.privacy');
Route::livewire('/pusat-bantuan', 'pages::public.help.index')->name('public.help');
Route::livewire('/daftar-mitra', 'pages::public.fundraiser.index')->name('public.fundraiser');
Route::livewire('/hubungi-kami', 'pages::public.contact.index')->name('public.contact');



include 'admin.php';
include 'fundraiser.php';

Route::get('/storage-link', function () {
    $exitCode = Artisan::call('storage:link');
    return 'Storage link has been created. Exit code: ' . $exitCode;
});
