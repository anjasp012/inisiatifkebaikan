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
