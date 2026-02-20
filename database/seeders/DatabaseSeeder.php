<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Bank;
use App\Models\Campaign;
use App\Models\CampaignCategory;
use App\Models\CampaignUpdate;
use App\Models\Distribution;
use App\Models\Donation;
use App\Models\Fundraiser;
use App\Models\NotificationTemplate;
use App\Models\Setting;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SETTINGS & CONFIGURATION
        $this->seedSettings();
        $this->command->info('✅ Settings seeded');

        // 2. NOTIFICATION TEMPLATES
        $this->seedNotificationTemplates();
        $this->command->info('✅ Notification Templates seeded');

        // 3. USERS (Admin, Fundraisers, Donatur)
        $users = $this->seedUsers();
        $admin = $users['admin'];
        $this->command->info('✅ Users seeded (admin@inisiatifkebaikan.org / password)');

        // 4. BANKS
        $banks = $this->seedBanks();
        $this->command->info('✅ Banks seeded');

        // 5. CATEGORIES
        $categories = $this->seedCategories();
        $this->command->info('✅ Campaign Categories seeded');

        // 6. FUNDRAISERS
        $fundraisers = $this->seedFundraisers($users['fundraisers']);
        $this->command->info('✅ Fundraisers seeded');

        // 7. CAMPAIGNS
        $campaigns = $this->seedCampaigns($categories, $fundraisers);
        $this->command->info('✅ Campaigns seeded');

        // 8. DONATIONS
        $this->seedDonations($campaigns, $users['donatur']);
        $this->command->info('✅ Donations seeded');

        // 9. UPDATES
        $this->seedCampaignUpdates($campaigns);
        $this->command->info('✅ Campaign Updates seeded');

        // 10. DISTRIBUTIONS
        $this->seedDistributions($campaigns);
        $this->command->info('✅ Distributions seeded');

        // 11. ARTICLES
        $this->seedArticles($admin);
        $this->command->info('✅ Articles seeded');
    }

    private function seedSettings()
    {
        $settings = [
            'website_name' => 'Wahdah Inisiatif Kebaikan',
            'website_description' => 'Platform donasi dan penghimpunan dana sosial terpercaya untuk mewujudkan inisiatif kebaikan di seluruh nusantara.',
            'whatsapp_number' => '62811444555',
            'footer_text' => '© 2026 Wahdah Inisiatif Kebaikan. Menebar Kebaikan, Menggapai Berkah.',
            'tripay_merchant_code' => 'T15582',
            'tripay_api_key' => 'DEV-L9Xxxxxxxxxxxxxxxxxxxxxxxx',
            'tripay_private_key' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
            'tripay_mode' => 'sandbox',
            'midtrans_merchant_id' => 'G123456789',
            'midtrans_client_key' => 'SB-Mid-client-xxxxxxxxxxxx',
            'midtrans_server_key' => 'SB-Mid-server-xxxxxxxxxxxx',
            'midtrans_mode' => 'sandbox',
            'whacenter_device_id' => 'device_xxxxxxxx',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }
    }

    private function seedNotificationTemplates()
    {
        $templates = [
            [
                'slug' => 'donation-created',
                'name' => 'Donasi Dibuat (Pending)',
                'content' => "Assalamu'alaikum {donor_name},\n\nTerima kasih telah berdonasi melalui Wahdah Inisiatif Kebaikan.\n\nDetail Donasi:\nProgram: {campaign_title}\nJumlah: {amount}\nMetode: {payment_channel}\nKode Bayar: {payment_code}\n\nSilakan selesaikan pembayaran Anda sebelum masa berlaku berakhir. Semoga Allah membalas kebaikan Anda dengan keberkahan berlipat.",
            ],
            [
                'slug' => 'donation-confirmed',
                'name' => 'Donasi Berhasil (Success)',
                'content' => "Alhamdulillah {donor_name},\n\nDonasi Anda untuk program \"{campaign_title}\" sebesar {amount} telah kami terima dengan baik.\n\nTerima kasih atas kepercayaannya. Donasi Anda sangat berarti bagi para penerima manfaat. Kami akan terus update progress program melalui website.\n\nJazaakumullaahu khairan.",
            ],
            [
                'slug' => 'donation-rejected',
                'name' => 'Donasi Dibatalkan (Failed)',
                'content' => "Mohon maaf {donor_name},\n\nDonasi Anda untuk program \"{campaign_title}\" tidak dapat dikonfirmasi/dibatalkan.\n\nJika ini adalah kekeliruan, silakan hubungi layanan pelanggan kami melalui WhatsApp di nomor yang tertera di website.\n\nTerima kasih.",
            ],
            [
                'slug' => 'otp-login',
                'name' => 'Login OTP',
                'content' => "Kode OTP Anda untuk login ke Wahdah Inisiatif Kebaikan adalah: *{otp_code}*\n\nKode ini berlaku selama 5 menit. Jangan berikan kode ini kepada siapapun termasuk pihak Wahdah Inisiatif.",
            ],
        ];

        foreach ($templates as $tpl) {
            NotificationTemplate::updateOrCreate(['slug' => $tpl['slug']], $tpl);
        }
    }

    private function seedUsers()
    {
        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@inisiatifkebaikan.org'],
            ['name' => 'Admin Inisiatif', 'password' => Hash::make('password'), 'role' => 'admin', 'user_verified_at' => now(), 'phone' => '628111111111']
        );

        // Fundraisers
        $fundraiserNames = ['Wahdah Inisiatif Jakarta', 'Relawan Kebaikan Bandung', 'Peduli Ummat Makassar'];
        $fundraisers = [];
        foreach ($fundraiserNames as $i => $name) {
            $fundraisers[] = User::firstOrCreate(
                ['email' => 'fundraiser' . ($i + 1) . '@inisiatifkebaikan.org'],
                ['name' => $name, 'password' => Hash::make('password'), 'role' => 'fundraiser', 'user_verified_at' => now(), 'phone' => '6282222222' . ($i + 1)]
            );
        }

        // Donatur (Sample Registered Users)
        $donaturNames = ['Abdullah Azam', 'Siti Rahma', 'Budi Santoso', 'Anjas Mara', 'Zulfikar Ali'];
        $donaturs = [];
        foreach ($donaturNames as $i => $name) {
            $donaturs[] = User::firstOrCreate(
                ['email' => Str::slug($name) . '@email.com'],
                ['name' => $name, 'password' => Hash::make('password'), 'role' => 'donatur', 'user_verified_at' => now(), 'phone' => '6283333333' . ($i + 1)]
            );
        }

        return [
            'admin' => $admin,
            'fundraisers' => $fundraisers,
            'donatur' => $donaturs
        ];
    }

    private function seedBanks()
    {
        $banksData = [
            ['name' => 'Bank Central Asia (BCA)', 'no' => '1396-316-316', 'an' => 'Yayasan Wahdah Inisiatif Kebaikan', 'logo' => 'assets/images/banks/bca.png', 'channel' => 'BCA'],
            ['name' => 'Bank Syariah Indonesia (BSI)', 'no' => '76000-454-68', 'an' => 'Yayasan Wahdah Inisiatif Kebaikan', 'logo' => 'assets/images/banks/bsi.png', 'channel' => 'BSI'],
            ['name' => 'Bank Mandiri', 'no' => '13000-2578-6164', 'an' => 'Yayasan Wahdah Inisiatif Kebaikan', 'logo' => 'assets/images/banks/mandiri.png', 'channel' => 'MANDIRI'],
            ['name' => 'Bank Rakyat Indonesia (BRI)', 'no' => '0137-0100-2316-563', 'an' => 'Yayasan Wahdah Inisiatif Kebaikan', 'logo' => 'assets/images/banks/bri.png', 'channel' => 'BRI'],
            ['name' => 'Bank Muamalat', 'no' => '1050-0183-58', 'an' => 'Yayasan Wahdah Inisiatif Kebaikan', 'logo' => 'assets/images/banks/muamalat.png', 'channel' => 'MUAMALAT'],
        ];

        $banks = [];
        foreach ($banksData as $b) {
            $banks[] = Bank::updateOrCreate(
                ['account_number' => $b['no']],
                [
                    'bank_name' => $b['name'],
                    'account_name' => $b['an'],
                    'logo' => $b['logo'],
                    'type' => 'manual',
                    'method' => 'manual',
                    'is_active' => true,
                ]
            );
        }
        return $banks;
    }

    private function seedCategories()
    {
        $data = [
            ['name' => 'Kemanusiaan', 'icon' => 'bi bi-people-fill'],
            ['name' => 'Pendidikan', 'icon' => 'bi bi-mortarboard-fill'],
            ['name' => 'Kesehatan', 'icon' => 'bi bi-heart-pulse-fill'],
            ['name' => 'Bencana Alam', 'icon' => 'bi bi-umbrella-fill'],
            ['name' => 'Dakwah & Religi', 'icon' => 'bi bi-moon-stars-fill'],
            ['name' => 'Lingkungan', 'icon' => 'bi bi-tree-fill'],
        ];

        $categories = [];
        foreach ($data as $cat) {
            $categories[] = CampaignCategory::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                ['name' => $cat['name'], 'icon' => $cat['icon'], 'is_active' => true]
            );
        }
        return $categories;
    }

    private function seedFundraisers($users)
    {
        $fundraisers = [];
        $descriptions = [
            'Wahdah Inisiatif Jakarta berfokus pada pemberdayaan yatim dan dhuafa di wilayah Jabodetabek melalui program pendidikan dan kesehatan gratis.',
            'Relawan Kebaikan Bandung adalah komunitas sosial yang aktif membantu korban bencana alam dan pembangunan masjid di pelosok Jawa Barat.',
            'Peduli Ummat Makassar merupakan lembaga zakat daerah yang berkomitmen mengentaskan kemiskinan melalui pemberdayaan ekonomi syariah.'
        ];

        foreach ($users as $i => $user) {
            $fundraisers[] = Fundraiser::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'foundation_name' => $user->name,
                    'slug' => Str::slug($user->name),
                    'about' => $descriptions[$i] ?? 'Kami adalah lembaga yang berkomitmen melayani ummat dan menyebarkan kebaikan.',
                    'office_address' => 'Jl. Kebaikan No. ' . ($i + 1) . ', Kota ' . ($i == 0 ? 'Jakarta' : ($i == 1 ? 'Bandung' : 'Makassar')),
                    'status' => 'approved',
                    'logo_image' => 'fundraisers/logos/dummy-logo-' . ($i + 1) . '.jpg',
                ]
            );
        }
        return $fundraisers;
    }

    private function seedCampaigns($categories, $fundraisers)
    {
        $campaigns = [];
        $titles = [
            'Bantu Renovasi Madrasah di Pelosok Desa',
            'Sedekah Makan Siang untuk Relawan Medis',
            'Emergency: Bantuan Banjir Bandang Luwu',
            'Wakaf Mushaf Al-Quran Pelosok Nusantara',
            'Beasiswa Pendidikan Yatim Dhuafa Hebat',
            'Pembangunan Sumur Air Bersih Desa Kering',
            'Tebar Hewan Qurban Hingga Pedalaman',
            'Santunan Operasi Katarak Lansia Kurang Mampu',
            'Patungan Motor Dakwah untuk Dai Pedalaman',
            'Peduli Palestina: Bantuan Medis dan Pangan'
        ];

        foreach ($titles as $i => $title) {
            $isUrgent = Str::contains($title, 'Emergency');
            $fundraiser = $fundraisers[array_rand($fundraisers)];

            $campaigns[] = Campaign::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title,
                    'category_id' => $categories[array_rand($categories)]->id,
                    'fundraiser_id' => $fundraiser->id,
                    'target_amount' => rand(10, 500) * 1000000,
                    'collected_amount' => 0,
                    'start_date' => now(),
                    'end_date' => now()->addDays(rand(30, 90)),
                    'description' => '<h3>Tentang Program Ini</h3><p>Program ' . $title . ' dirancang untuk membantu mereka yang membutuhkan. Mari kita sebarkan kebaikan ini ke seluruh lapisan masyarakat agar manfaatnya semakin luas.</p><p>Setiap rupiah yang Anda donasikan akan sangat berarti bagi kelancaran program ini.</p>',
                    'thumbnail' => 'campaigns/dummy-' . ($i + 1) . '.jpg',
                    'status' => 'active',
                    'is_emergency' => $isUrgent,
                    'is_priority' => $i < 2,
                    'is_optimized' => $i < 4,
                    'is_slider' => $i < 3,
                ]
            );
        }
        return $campaigns;
    }

    private function seedDonations($campaigns, $donaturs)
    {
        foreach ($campaigns as $campaign) {
            $numDonations = rand(3, 8);
            $totalCollected = 0;

            for ($i = 0; $i < $numDonations; $i++) {
                $amount = rand(10, 500) * 10000;
                $donatur = $donaturs[array_rand($donaturs)];

                Donation::create([
                    'transaction_id' => 'INV-' . strtoupper(Str::random(10)),
                    'campaign_id' => $campaign->id,
                    'user_id' => $donatur->id,
                    'amount' => $amount,
                    'donor_name' => $donatur->name,
                    'donor_email' => $donatur->email,
                    'donor_phone' => $donatur->phone,
                    'status' => 'success',
                    'payment_channel' => 'BSI',
                    'payment_method' => 'manual',
                    'payment_code' => '76000' . rand(1000, 9999),
                    'paid_at' => now()->subDays(rand(0, 10)),
                    'is_anonymous' => rand(0, 1) == 1,
                    'message' => rand(0, 1) == 1 ? 'Semoga berkah dan bermanfaat.' : null,
                ]);

                $totalCollected += $amount;
            }

            $campaign->update(['collected_amount' => $totalCollected]);
        }
    }

    private function seedCampaignUpdates($campaigns)
    {
        foreach ($campaigns as $campaign) {
            if (rand(0, 1) == 1) { // 50% chance to have updates
                $numUpdates = rand(1, 4);
                for ($i = 0; $i < $numUpdates; $i++) {
                    CampaignUpdate::create([
                        'campaign_id' => $campaign->id,
                        'title' => 'Update Kondisi Program Tahap ' . ($i + 1),
                        'content' => '<p>Alhamdulillah, berkat dukungan para donatur, program ini terus berjalan dengan baik. Kami sedang mempersiapkan tim untuk tahap pendistribusian berikutnya.</p>',
                        'published_at' => now()->subDays($numUpdates - $i + 5),
                        'image' => 'campaign-updates/update-' . rand(1, 5) . '.jpg',
                    ]);
                }
            }
        }
    }

    private function seedDistributions($campaigns)
    {
        foreach ($campaigns as $campaign) {
            if (rand(0, 1) == 1) { // 50% chance to have distributions
                $numDist = rand(1, 2);
                for ($i = 0; $i < $numDist; $i++) {
                    $amount = rand(5, 50) * 100000;
                    Distribution::create([
                        'campaign_id' => $campaign->id,
                        'amount' => $amount,
                        'recipient_name' => 'Warga Kelurahan ' . ($i + 1) . ' Peduli',
                        'distribution_date' => now()->subDays(rand(1, 5)),
                        'description' => '<p>Telah disalurkan bantuan berupa paket sembako dan kebutuhan pokok senilai Rp ' . number_format($amount, 0, ',', '.') . ' kepada warga yang membutuhkan di sekitar lokasi program.</p><p>Semoga menjadi amal jariyah bagi para donatur.</p>',
                        'file_path' => 'distributions/dist-' . rand(1, 5) . '.jpg',
                    ]);
                }
            }
        }
    }

    private function seedArticles($admin)
    {
        $articles = [
            ['title' => '5 Keutamaan Sedekah Jariyah Yang Mengalir Terus', 'category' => 'Edukasi'],
            ['title' => 'Relawan Wahdah Tembus Lokasi Gempa Terpencil', 'category' => 'Berita'],
            ['title' => 'Kisah Mak Inah: Berbagi di Tengah Keterbatasan', 'category' => 'Inspirasi'],
            ['title' => 'Manfaat Wakaf Quran Bagi Generasi Mendatang', 'category' => 'Edukasi'],
        ];

        foreach ($articles as $a) {
            Article::updateOrCreate(
                ['slug' => Str::slug($a['title'])],
                [
                    'title' => $a['title'],
                    'category' => $a['category'],
                    'author_id' => $admin->id,
                    'thumbnail' => 'articles/dummy-article.jpg',
                    'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
                    'is_published' => true,
                ]
            );
        }
    }
}
