<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Illuminate\Support\Facades\View;

new #[Layout('layouts.app')] class extends Component {
    public function mount()
    {
        $seoData = new SEOData(title: 'Pusat Bantuan | Inisiatif Kebaikan', description: 'Jawaban atas pertanyaan umum seputar Inisiatif Kebaikan.');
        View::share('seoData', $seoData);
    }
};
?>

<div>
    <x-app.navbar-secondary title="Pusat Bantuan" />

    <section class="help-center-section">
        <div class="container-fluid">
            <h6 class="fw-bold mb-3 px-1">Pertanyaan Umum (FAQ)</h6>

            <div class="accordion shadow-sm" id="helpAccordion">
                <div class="accordion-item border-0 mb-2 rounded-3 overflow-hidden">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold shadow-none" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseOne">
                            Bagaimana cara berdonasi?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                        <div class="accordion-body text-muted pt-0 small">
                            Pilih kampanye yang ingin Anda bantu, klik tombol "Donasi Sekarang", masukkan nominal, pilih
                            metode pembayaran, dan selesaikan transfer.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 mb-2 rounded-3 overflow-hidden">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold shadow-none" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                            Apakah donasi saya aman?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                        <div class="accordion-body text-muted pt-0 small">
                            Ya, Inisiatif Kebaikan menggunakan sistem pembayaran terverifikasi dan mengenkripsi data
                            transaksi Anda. Dana disalurkan secara transparan.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 mb-2 rounded-3 overflow-hidden">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold shadow-none" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseThree">
                            Metode pembayaran apa saja yang tersedia?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                        <div class="accordion-body text-muted pt-0 small">
                            Kami mendukung transfer bank (BCA, Mandiri, BNI, BRI), E-Wallet (GoPay, OVO, Dana, LinkAja),
                            dan pembayaran via QRIS.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0 mb-2 rounded-3 overflow-hidden">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold shadow-none" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapseFour">
                            Bagaimana jika saya salah transfer nominal?
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#helpAccordion">
                        <div class="accordion-body text-muted pt-0 small">
                            Hubungi layanan pelanggan kami melalui WhatsApp atau Email dengan menyertakan bukti transfer
                            untuk proses verifikasi manual.
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3 p-4 shadow-sm border-0 mt-4 text-center">
                <div class="mb-3">
                    <i class="bi bi-headset display-1 text-primary opacity-25"></i>
                </div>
                <h6 class="fw-bold mb-2">Butuh Bantuan Lain?</h6>
                <p class="text-muted small mb-4">Tim support kami siap membantu kendala Anda.</p>
                <a href="{{ route('public.contact') }}" class="btn btn-outline-primary fw-bold w-100 py-2 rounded-pill"
                    wire:navigate>
                    <i class="bi bi-chat-dots me-2"></i> Hubungi Kami
                </a>
            </div>
        </div>
    </section>

    <x-app.bottom-nav />
</div>
