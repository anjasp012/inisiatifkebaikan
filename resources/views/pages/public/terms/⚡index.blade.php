<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Illuminate\Support\Facades\View;

new #[Layout('layouts.app')] class extends Component {
    public function mount()
    {
        $seoData = new SEOData(title: 'Syarat & Ketentuan | Inisiatif Kebaikan', description: 'Syarat dan Ketentuan penggunaan layanan Inisiatif Kebaikan.');
        View::share('seoData', $seoData);
    }
};
?>

<div>
    <x-app.navbar-secondary title="Syarat & Ketentuan" />

    <section class="terms-section">
        <div class="container-fluid">
            <div class="bg-white rounded-3 p-4 shadow-sm border-0">
                <h5 class="fw-bold mb-3">Syarat & Ketentuan Pengguna</h5>
                <ol class="list-decimal ps-3 text-muted">
                    <li class="mb-3">
                        <strong class="text-dark d-block mb-1">Pendaftaran Akun</strong>
                        Pengguna wajib mengisi data diri dengan benar dan jujur saat mendaftar. Kami berhak
                        menonaktifkan akun jika ditemukan data palsu.
                    </li>
                    <li class="mb-3">
                        <strong class="text-dark d-block mb-1">Penggalangan Dana</strong>
                        Setiap kampanye penggalangan dana harus memiliki tujuan yang jelas, tidak melanggar hukum, dan
                        dapat dipertanggungjawabkan.
                    </li>
                    <li class="mb-3">
                        <strong class="text-dark d-block mb-1">Donasi</strong>
                        Donasi yang telah diberikan bersifat sukarela dan tidak dapat dikembalikan kecuali terjadi
                        pembatalan kampanye oleh sistem.
                    </li>
                    <li class="mb-3">
                        <strong class="text-dark d-block mb-1">Biaya Administrasi</strong>
                        Platform Inisiatif Kebaikan mungkin mengenakan biaya administrasi kecil dari total donasi
                        terkumpul untuk biaya operasional platform.
                    </li>
                    <li class="mb-3">
                        <strong class="text-dark d-block mb-1">Penyalahgunaan</strong>
                        Tindakan penipuan, pencucian uang, atau penyalahgunaan dana akan diproses sesuai hukum yang
                        berlaku di Indonesia.
                    </li>
                </ol>
            </div>
        </div>
    </section>

    <x-app.bottom-nav />
</div>
