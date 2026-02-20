<?php

namespace App\Exports;

use App\Models\Donation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DonasiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $search;
    protected $status;

    public function __construct($search = null, $status = 'all')
    {
        $this->search = $search;
        $this->status = $status;
    }

    public function collection()
    {
        return Donation::with(['campaign', 'bank'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('donor_name', 'like', '%' . $this->search . '%')
                        ->orWhere('transaction_id', 'like', '%' . $this->search . '%')
                        ->orWhere('donor_phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status !== 'all', function ($query) {
                $query->where('status', $this->status);
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID Transaksi',
            'Nama Donatur',
            'No Telepon/WA',
            'Program Campaign',
            'Nominal Donasi (Rp)',
            'Metode Pembayaran',
            'Channel / Tipe',
            'Status',
            'Tanggal Dibuat',
        ];
    }

    public function map($donation): array
    {
        $method = $donation->bank ? $donation->bank->bank_name : str_replace('_', ' ', $donation->payment_method);
        $channel = $donation->bank ? $donation->bank->type : $donation->payment_channel;

        return [
            $donation->transaction_id,
            $donation->donor_name,
            $donation->donor_phone ?? '-',
            $donation->campaign->title ?? 'Campaign Terhapus',
            $donation->amount,
            ucwords($method),
            $channel ? strtoupper($channel) : '-',
            ucfirst($donation->status),
            $donation->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
