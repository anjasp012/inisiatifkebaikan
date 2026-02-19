<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;

new #[Layout('layouts.app')] class extends Component {
    public function mount()
    {
        $seoData = new SEOData(title: 'Hubungi Kami | Inisiatif Kebaikan', description: 'Hubungi tim Inisiatif Kebaikan untuk pertanyaan dan bantuan.');
        View::share('seoData', $seoData);
    }
};
?>

<div>
    <x-app.navbar-secondary title="Hubungi Kami" />

    <section class="contact-section">
        <div class="container-fluid">
            <!-- Contact Info -->
            <div class="row g-3">
                <div class="col-12">
                    <div class="bg-white rounded-3 p-3 shadow-sm border-0 d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-success bg-opacity-10 text-success p-3 d-flex align-items-center justify-content-center"
                            style="width: 50px; height: 50px;">
                            <i class="bi bi-whatsapp fs-4"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block fw-bold ls-1 text-uppercase extra-small">WhatsApp
                                Support</small>
                            <a href="https://wa.me/6281234567890" target="_blank"
                                class="text-dark decoration-none fw-bold fs-6 stretched-link">+62 812-3456-7890</a>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="bg-white rounded-3 p-3 shadow-sm border-0 d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-primary bg-opacity-10 text-primary p-3 d-flex align-items-center justify-content-center"
                            style="width: 50px; height: 50px;">
                            <i class="bi bi-envelope fs-4"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block fw-bold ls-1 text-uppercase extra-small">Email
                                Resmi</small>
                            <a href="mailto:support@inisiatifkebaikan.org"
                                class="text-dark decoration-none fw-bold fs-6 stretched-link">support@inisiatif.org</a>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="bg-white rounded-3 p-3 shadow-sm border-0 d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-warning bg-opacity-10 text-warning p-3 d-flex align-items-center justify-content-center"
                            style="width: 50px; height: 50px;">
                            <i class="bi bi-geo-alt fs-4"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block fw-bold ls-1 text-uppercase extra-small">Kantor
                                Pusat</small>
                            <span class="text-dark fw-bold small">Jl. Kebaikan Utama No. 123, Jakarta Selatan,
                                12345</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <x-app.bottom-nav />
</div>
