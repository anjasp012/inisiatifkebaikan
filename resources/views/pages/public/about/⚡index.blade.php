<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Illuminate\Support\Facades\View;

new #[Layout('layouts.app')] class extends Component {
    public function mount()
    {
        $seoData = new SEOData(title: 'Tentang Kami | ' . \App\Models\Setting::get('website_name', 'Inisiatif Kebaikan'), description: 'Mengenal lebih dekat ' . \App\Models\Setting::get('website_name', 'Inisiatif Kebaikan') . '.');
        View::share('seoData', $seoData);
    }
};
?>

<div>
    <x-app.navbar-secondary title="Tentang Kami" />

    <section class="about-section">
        <div class="container-fluid">
            <div class="bg-white rounded-3 p-4 shadow-sm border-0">
                <h5 class="fw-bold mb-3">Tentang {{ \App\Models\Setting::get('website_name', 'Inisiatif Kebaikan') }}
                </h5>
                <p class="text-muted mb-3">
                    {{ \App\Models\Setting::get('website_name', 'Inisiatif Kebaikan') }} adalah platform gotong royong
                    digital yang menghubungkan jutaan orang baik untuk
                    saling membantu sesama. Kami percaya bahwa setiap inisiatif kecil dapat membawa perubahan besar bagi
                    mereka yang membutuhkan.
                </p>
                <p class="text-muted mb-3">
                    Berdiri sejak tahun 2024, kami telah memfasilitasi ribuan kampanye sosial mulai dari bantuan medis,
                    bencana alam, pendidikan, hingga pemberdayaan ekonomi masyarakat.
                </p>
                <p class="text-muted mb-0">
                    Visi kami adalah menjadi jembatan kebaikan terpercaya di Indonesia yang transparan, akuntabel, dan
                    berdampak luas.
                </p>
            </div>
        </div>
    </section>

    <x-app.bottom-nav />
</div>
