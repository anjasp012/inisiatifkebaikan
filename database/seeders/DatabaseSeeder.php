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
        $this->command->info('✅ Users seeded (admin@inisiatif.com / password)');

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
        $campaigns = $this->seedCampaigns($categories, $fundraisers, $admin);
        $this->command->info('✅ Campaigns seeded');

        // 8. DONATIONS
        $this->seedDonations($campaigns, $banks, $users['donatur']);
        $this->command->info('✅ Donations seeded');

        // 9. WITHDRAWALS & DISTRIBUTIONS
        $this->seedWithdrawalsAndDistributions($campaigns);
        $this->command->info('✅ Withdrawals & Distributions seeded');

        // 10. ARTICLES
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
            ['email' => 'admin@inisiatif.com'],
            ['name' => 'Admin Inisiatif', 'password' => Hash::make('password'), 'role' => 'admin', 'user_verified_at' => now(), 'phone' => '628111111111']
        );

        // Fundraisers
        $fundraiserNames = ['Wahdah Inisiatif Jakarta', 'Relawan Kebaikan Bandung', 'Peduli Ummat Makassar'];
        $fundraisers = [];
        foreach ($fundraiserNames as $i => $name) {
            $fundraisers[] = User::firstOrCreate(
                ['email' => 'fundraiser' . ($i + 1) . '@inisiatif.com'],
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
            ['name' => 'Bank Syariah Indonesia (BSI)', 'no' => '76000-454-68', 'an' => 'Yayasan Wahdah Inisiatif Kebaikan', 'logo' => 'assets/images/banks/bsi.png', 'channel' => 'BSI'],
            ['name' => 'Bank Rakyat Indonesia (BRI)', 'no' => '0137-0100-2316-563', 'an' => 'Yayasan Wahdah Inisiatif Kebaikan', 'logo' => 'assets/images/banks/bri.png', 'channel' => 'BRI'],
            ['name' => 'Bank Mandiri', 'no' => '13000-2578-6164', 'an' => 'Yayasan Wahdah Inisiatif Kebaikan', 'logo' => 'assets/images/banks/mandiri.png', 'channel' => 'MANDIRI'],
            ['name' => 'Bank Central Asia (BCA)', 'no' => '1396-316-316', 'an' => 'Yayasan Wahdah Inisiatif Kebaikan', 'logo' => 'assets/images/banks/bca.png', 'channel' => 'BCA'],
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
        foreach ($users as $i => $user) {
            $fundraisers[] = Fundraiser::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'foundation_name' => $user->name,
                    'status' => 'approved',
                    'bank_name' => 'BSI',
                    'bank_account_name' => $user->name,
                    'bank_account_number' => '71234567' . $i,
                    'office_address' => 'Jl. Kebaikan No. ' . ($i + 1),
                ]
            );
        }
        return $fundraisers;
    }

    private function seedCampaigns($categories, $fundraisers, $admin)
    {
        $data = [
            ['title' => 'Bantu Renovasi Masjid Al-Ikhlas Pelosok Jambi', 'target' => 2500000000, 'emergency' => false, 'priority' => true],
            ['title' => 'Darurat Gempa: Bantuan Logistik Untuk Korban', 'target' => 5000000000, 'emergency' => true, 'priority' => true],
            ['title' => 'Sedekah Quran Untuk Santri Penghafal Quran', 'target' => 500000000, 'emergency' => false, 'priority' => false],
            ['title' => 'Beasiswa Pendidikan 100 Anak Yatim Piatu', 'target' => 1500000000, 'emergency' => false, 'priority' => true],
            ['title' => 'Bantuan Alat Kesehatan Untuk RS Terpencil', 'target' => 3000000000, 'emergency' => false, 'priority' => false],
            ['title' => 'Aksi Tanam 1000 Pohon Mangrove di Pesisir Jakarta', 'target' => 250000000, 'emergency' => false, 'priority' => false],
        ];

        $campaigns = [];
        foreach ($data as $i => $item) {
            $campaigns[] = Campaign::updateOrCreate(
                ['slug' => Str::slug($item['title'])],
                [
                    'category_id' => $categories[$i % count($categories)]->id,
                    'fundraiser_id' => $fundraisers[$i % count($fundraisers)]->id,
                    'user_id' => $admin->id,
                    'title' => $item['title'],
                    'description' => 'Mari bergandengan tangan untuk mendukung ' . $item['title'] . '. Setiap rupiah yang Anda sumbangkan akan sangat bermakna bagi mereka yang membutuhkan.',
                    'target_amount' => $item['target'],
                    'collected_amount' => 0,
                    'status' => 'active',
                    'is_emergency' => $item['emergency'],
                    'is_priority' => $item['priority'],
                    'is_slider' => $item['priority'],
                    'thumbnail' => 'campaigns/dummy-campaign.jpg',
                    'start_date' => now()->subDays(10),
                    'end_date' => now()->addDays(60),
                ]
            );
        }
        return $campaigns;
    }

    private function seedDonations($campaigns, $banks, $donaturs)
    {
        $anonymousNames = ['Hamba Allah', 'Hamba Allah Makassar', 'Sahabat Kebaikan'];
        $statuses = ['success', 'success', 'success', 'success', 'pending', 'failed'];

        foreach ($campaigns as $campaign) {
            $count = rand(20, 50);
            for ($i = 0; $i < $count; $i++) {
                $status = $statuses[array_rand($statuses)];
                $amount = rand(10, 500) * 10000;
                $bank = $banks[array_rand($banks)];

                // 50% chance registered donatur, 50% anonymous/guest
                $isRegistered = rand(0, 1);
                $donatur = $isRegistered ? $donaturs[array_rand($donaturs)] : null;

                $donationData = [
                    'transaction_id' => 'INV-' . strtoupper(Str::random(10)),
                    'campaign_id' => $campaign->id,
                    'bank_id' => $bank->id,
                    'user_id' => $donatur ? $donatur->id : null,
                    'donor_name' => $donatur ? $donatur->name : $anonymousNames[array_rand($anonymousNames)],
                    'donor_phone' => '08' . rand(111111111, 899999999),
                    'donor_email' => $donatur ? $donatur->email : 'guest' . rand(1, 1000) . '@email.com',
                    'amount' => $amount,
                    'status' => $status,
                    'payment_method' => 'manual_transfer',
                    'payment_channel' => $bank->bank_name,
                    'payment_code' => $bank->account_number,
                    'message' => $i % 4 === 0 ? 'Semoga berkah dan bermanfaat. Aamiin.' : null,
                    'amin_count' => $i % 4 === 0 ? rand(0, 50) : 0,
                    'paid_at' => $status === 'success' ? now()->subDays(rand(0, 7)) : null,
                    'created_at' => now()->subDays(rand(0, 7)),
                ];

                $donation = Donation::create($donationData);

                if ($status === 'success') {
                    $campaign->increment('collected_amount', $amount);
                }
            }
        }
    }

    private function seedWithdrawalsAndDistributions($campaigns)
    {
        foreach ($campaigns as $campaign) {
            if ($campaign->collected_amount > 10000000) {
                $withdrawAmount = $campaign->collected_amount * 0.1;
                // Withdrawal
                Withdrawal::create([
                    'fundraiser_id' => $campaign->fundraiser_id,
                    'campaign_id' => $campaign->id,
                    'amount' => $withdrawAmount,
                    'net_amount' => $withdrawAmount * 0.95,
                    'status' => 'success',
                    'notes' => 'Pencairan operasional program lapangan.',
                    'file_path' => 'withdrawals/dummy-withdrawal.jpg',
                ]);

                // Distribution
                Distribution::create([
                    'campaign_id' => $campaign->id,
                    'amount' => $withdrawAmount * 0.95,
                    'recipient_name' => 'Masyarakat Terdampak ' . $campaign->title,
                    'distribution_date' => now()->subDays(2),
                    'description' => 'Penyaluran logistik tahap awal berdasarkan hasil pengumpulan dana sementara.',
                    'file_path' => 'distributions/dummy-distribution.jpg',
                ]);

                // Campaign Update
                CampaignUpdate::create([
                    'campaign_id' => $campaign->id,
                    'title' => 'Laporan Penyaluran Dana Operasional',
                    'content' => 'Alhamdulillah, sebagian dana telah disalurkan untuk kebutuhan mendesak di lokasi program. Terima kasih atas dukungan #SahabatKebaikan!',
                    'published_at' => now()->subDays(1),
                ]);
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
