<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/admin', '/admin/dashboard')->name('admin');
Route::livewire('/admin/dashboard', 'pages::admin.dashboard.index')->name('admin.dashboard');

Route::livewire('/admin/kategori-campaign', 'pages::admin.kategori-campaign.index')->name('admin.kategori-campaign');
Route::livewire('/admin/kategori-campaign/tambah', 'pages::admin.kategori-campaign.tambah')->name('admin.kategori-campaign.tambah');
Route::livewire('/admin/kategori-campaign/ubah/{campaignCategory:slug}', 'pages::admin.kategori-campaign.ubah')->name('admin.kategori-campaign.ubah');

Route::livewire('/admin/campaign', 'pages::admin.campaign.index')->name('admin.campaign');
Route::livewire('/admin/campaign/tambah', 'pages::admin.campaign.tambah')->name('admin.campaign.tambah');
Route::livewire('/admin/campaign/ubah/{campaign:slug}', 'pages::admin.campaign.ubah')->name('admin.campaign.ubah');
Route::livewire('/admin/campaign/{campaign:slug}/updates', 'pages::admin.campaign.update.⚡index')->name('admin.campaign.updates');
Route::livewire('/admin/campaign/{campaign:slug}/updates/tambah', 'pages::admin.campaign.update.⚡tambah')->name('admin.campaign.updates.tambah');
Route::livewire('/admin/campaign/{campaign:slug}/updates/ubah/{update}', 'pages::admin.campaign.update.⚡ubah')->name('admin.campaign.updates.ubah');

Route::livewire('/admin/donasi', 'pages::admin.donasi.index')->name('admin.donasi');
Route::livewire('/admin/donasi/tambah', 'pages::admin.donasi.tambah')->name('admin.donasi.tambah');
Route::livewire('/admin/donasi/detail/{donation}', 'pages::admin.donasi.detail')->name('admin.donasi.detail');

Route::livewire('/admin/artikel', 'pages::admin.artikel.index')->name('admin.artikel');
Route::livewire('/admin/artikel/tambah', 'pages::admin.artikel.tambah')->name('admin.artikel.tambah');
Route::livewire('/admin/artikel/ubah/{article:slug}', 'pages::admin.artikel.ubah')->name('admin.artikel.ubah');

Route::livewire('/admin/fundraiser', 'pages::admin.fundraiser.index')->name('admin.fundraiser');
Route::livewire('/admin/fundraiser/{fundraiser}', 'pages::admin.fundraiser.detail')->name('admin.fundraiser.detail');

Route::livewire('/admin/donatur', 'pages::admin.donatur.index')->name('admin.donatur');

Route::livewire('/admin/pencairan', 'pages::admin.pencairan.index')->name('admin.pencairan');
Route::livewire('/admin/pencairan/tambah', 'pages::admin.pencairan.⚡tambah')->name('admin.pencairan.tambah');
Route::livewire('/admin/pencairan/{withdrawal}', 'pages::admin.pencairan.detail')->name('admin.pencairan.detail');

Route::livewire('/admin/distribusi', 'pages::admin.distribusi.index')->name('admin.distribusi');

Route::livewire('/admin/bank', 'pages::admin.bank.index')->name('admin.bank');
Route::livewire('/admin/bank/tambah', 'pages::admin.bank.tambah')->name('admin.bank.tambah');
Route::livewire('/admin/bank/ubah/{bank}', 'pages::admin.bank.ubah')->name('admin.bank.ubah');

Route::livewire('/admin/settings', 'pages::admin.settings.index')->name('admin.settings');
