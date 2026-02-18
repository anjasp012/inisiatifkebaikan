<?php

namespace Database\Seeders;

use App\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class NotificationTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'slug' => 'otp-login',
                'name' => 'Login OTP',
                'content' => "Kode OTP Anda untuk login ke Wahdah Inisiatif Kebaikan adalah: *{otp_code}*\n\nKode ini berlaku selama 5 menit. Jangan berikan kode ini kepada siapapun termasuk pihak Wahdah Inisiatif.",
            ],
            [
                'slug' => 'otp-register',
                'name' => 'Registrasi OTP',
                'content' => "Selamat Datang di Wahdah Inisiatif Kebaikan!\n\nKode OTP Anda untuk verifikasi pendaftaran adalah: *{otp_code}*\n\nMasukkan kode ini untuk menyelesaikan pendaftaran Anda. Terima kasih telah bergabung dalam gerakan kebaikan.",
            ],
        ];

        foreach ($templates as $tpl) {
            NotificationTemplate::updateOrCreate(['slug' => $tpl['slug']], $tpl);
        }
    }
}
