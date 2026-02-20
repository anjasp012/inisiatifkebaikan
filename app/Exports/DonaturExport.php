<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DonaturExport implements FromCollection, WithHeadings, WithMapping
{
    protected $search;

    public function __construct($search = null)
    {
        $this->search = $search;
    }

    public function collection()
    {
        return User::where('role', 'donatur')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->withCount([
                'donations' => function ($query) {
                    $query->where('status', 'success');
                }
            ])
            ->withSum([
                'donations' => function ($query) {
                    $query->where('status', 'success');
                }
            ], 'amount')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Lengkap',
            'Email',
            'No. Telepon',
            'Status Verifikasi',
            'Total Donasi (Rp)',
            'Jumlah Transaksi Berhasil',
            'Bergabung Tanggal',
        ];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->phone ?? '-',
            $user->isVerified() ? 'Verified' : 'Unverified',
            $user->donations_sum_amount ?? 0,
            $user->donations_count,
            $user->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
