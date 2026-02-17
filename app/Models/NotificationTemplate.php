<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    protected $guarded = ['id'];

    public static function sendStatusNotification($donation, $slug)
    {
        $template = self::where('slug', $slug)->first();
        if (!$template) return;

        $waService = new \App\Services\WhacenterService();
        $content = $template->content;

        $placeholders = [
            '{donor_name}' => $donation->donor_name,
            '{campaign_title}' => $donation->campaign->title ?? 'Program Kebaikan',
            '{amount}' => 'Rp. ' . number_format($donation->amount, 0, ',', '.'),
            '{payment_channel}' => $donation->payment_channel,
            '{payment_code}' => $donation->payment_code,
        ];

        foreach ($placeholders as $key => $value) {
            $content = str_replace($key, $value, $content);
        }

        $waService->sendMessage($donation->donor_phone, $content, $donation->id);
    }
}
