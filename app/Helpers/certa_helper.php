<?php

if (!function_exists('status_badge_class')) {
    /**
     * Mapping nilai kondisi -> class badge Bootstrap.
     * Dipakai di Data Pengecekan, Form CERTA (preview), dan Dashboard.
     */
    function status_badge_class(string $kondisi): string
    {
        return match ($kondisi) {
            'Normal', 'On', '75', '100' => 'text-success',
            'Standby', '25'             => 'text-primary',
            'Off', 'Tidak Normal', '0'  => 'text-danger fw-semibold',
            default                     => 'text-secondary',
        };
    }
}

if (!function_exists('kondisi_display')) {
    /** Tambahkan simbol % untuk aset bertipe percentage saat ditampilkan. */
    function kondisi_display(string $inputType, string $kondisi): string
    {
        return $inputType === 'percentage' ? $kondisi . '%' : $kondisi;
    }
}

if (!function_exists('shift_badge_class')) {
    function shift_badge_class(bool $selesai): string
    {
        return $selesai ? 'bg-success' : 'bg-danger';
    }
}

if (!function_exists('format_tanggal_indo')) {
    function format_tanggal_indo(string $date): string
    {
        $bulan = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];
        $ts = strtotime($date);

        return date('d', $ts) . ' ' . $bulan[(int) date('n', $ts)] . ' ' . date('Y', $ts);
    }
}
