<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Illuminate\Support\Facades\View;

new #[Layout('layouts.app')] class extends Component {
    public function mount()
    {
        $seoData = new SEOData(title: 'Kebijakan Privasi | Inisiatif Kebaikan', description: 'Kebijakan Privasi data pengguna Inisiatif Kebaikan.');
        View::share('seoData', $seoData);
    }
};
?>

<div>
    <x-app.navbar-secondary title="Kebijakan Privasi" />

    <section class="privacy-section py-5">
        <div class="container-fluid">
            <div class="bg-white rounded-3 p-4 shadow-sm border-0">
                <h5 class="fw-bold mb-3">Kebijakan Privasi Data</h5>
                <ol class="list-decimal ps-3 text-muted">
                    <li class="mb-3">
                        <strong class="text-dark d-block mb-1">Pengumpulan Data</strong>
                        Kami mengumpulkan informasi pribadi berupa nama, email, nomor telepon, dan data transaksi untuk
                        keperluan layanan.
                    </li>
                    <li class="mb-3">
                        <strong class="text-dark d-block mb-1">Penggunaan Data</strong>
                        Data digunakan untuk memproses donasi, verifikasi akun, komunikasi layanan, dan pengembangan
                        platform.
                    </li>
                    <li class="mb-3">
                        <strong class="text-dark d-block mb-1">Keamanan Data</strong>
                        Kami menerapkan standar keamanan enkripsi SSL untuk melindungi data Anda dari akses tidak sah.
                    </li>
                    <li class="mb-3">
                        <strong class="text-dark d-block mb-1">Berbagi Data</strong>
                        Kami tidak menjual data Anda. Data hanya dibagikan kepada pihak ketiga (seperti payment gateway)
                        jika diperlukan untuk proses transaksi.
                    </li>
                    <li class="mb-3">
                        <strong class="text-dark d-block mb-1">Hak Pengguna</strong>
                        Anda berhak mengakses, memperbaiki, atau meminta penghapusan data pribadi Anda sesuai ketentuan
                        yang berlaku.
                    </li>
                </ol>
            </div>
        </div>
    </section>

    <x-app.bottom-nav />
</div>
