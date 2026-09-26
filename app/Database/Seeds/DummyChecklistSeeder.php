<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\AssetModel;

/**
 * Jalankan SETELAH MasterDataSeeder:
 *   php spark db:seed DummyChecklistSeeder
 *
 * Men-generate data transaksi (checklist, checklist_detail, activity_log)
 * untuk 20 hari terakhir x 3 shift/hari, sehingga:
 *  - Dashboard  : Total Laporan, Pengecekan Hari Ini, Status Shift Hari Ini,
 *                 Donut Status Peralatan, Gauge Solar, Aktivitas Terbaru semua terisi.
 *  - Data Pengecekan : ada banyak card untuk dicoba filter tanggal & cari teknisi.
 *  - Analisis Data   : tiap aset punya riwayat kondisi utk line chart tren.
 *
 * Seeder ini AMAN dijalankan berulang kali — tabel transaksi di-truncate
 * dulu di awal agar tidak dobel data.
 */
class DummyChecklistSeeder extends Seeder
{
    /** Nama petugas dummy — dipakai bergantian secara acak. */
    private array $petugas = ['Subhan', 'Yodi', 'Reva', 'Mawar Cantika', 'Budi Santoso'];

    public function run()
    {
        // Pastikan master data sudah ada
        if ($this->db->table('m_asset')->countAllResults() === 0) {
            echo "Master data belum ada. Jalankan dulu: php spark db:seed MasterDataSeeder\n";
            return;
        }

        // Seed deterministik supaya hasil dummy data konsisten tiap kali di-generate ulang
        mt_srand(2026);

        // Reset tabel transaksi (matikan FK check sementara, MySQL tetap menolak
        // TRUNCATE selama ada tabel lain yang mereferensikannya via FK meski
        // urutannya sudah child -> parent)
        $this->db->disableForeignKeyChecks();
        $this->db->table('checklist_photo')->truncate();
        $this->db->table('activity_log')->truncate();
        $this->db->table('checklist_detail')->truncate();
        $this->db->table('checklist')->truncate();
        $this->db->enableForeignKeyChecks();

        $assets = $this->db->table('m_asset')->get()->getResultArray();
        $shifts = $this->db->table('m_shift')->orderBy('sort_order', 'ASC')->get()->getResultArray();

        $jumlahHari = 20; // 20 hari terakhir termasuk hari ini
        $today = new \DateTime('today');

        for ($h = $jumlahHari - 1; $h >= 0; $h--) {
            $tanggal = (clone $today)->modify("-{$h} days")->format('Y-m-d');
            $isToday = ($h === 0);

            foreach ($shifts as $shift) {
                // Shift malam hari ini sengaja dibuat "Belum Selesai" (sesuai contoh desain)
                $isMalamHariIni = $isToday && $shift['name'] === 'Malam';
                if ($isMalamHariIni) {
                    continue; // tidak ada laporan tersimpan -> otomatis "Belum Selesai" di Dashboard
                }

                $namaPetugas   = $this->petugas[array_rand($this->petugas)];
                $jamPengecekan = $this->jamSekitar($shift['jam_mulai']);

                $checklistId = $this->db->table('checklist')->insert([
                    'nama_petugas'   => $namaPetugas,
                    'tanggal'        => $tanggal,
                    'shift_id'       => $shift['id'],
                    'jam_pengecekan' => $jamPengecekan,
                    'catatan'        => (mt_rand(1, 100) <= 15) ? 'Kondisi ruangan aman, tidak ada temuan berarti.' : null,
                    'status_selesai' => 1,
                    'created_at'     => $tanggal . ' ' . $jamPengecekan,
                    'updated_at'     => $tanggal . ' ' . $jamPengecekan,
                ]) ? $this->db->insertID() : null;

                if (!$checklistId) {
                    continue;
                }

                // Skenario khusus: checklist paling terakhir (hari ini, shift terakhir yang tersimpan)
                // sengaja dibuat ada 2 aset bermasalah, supaya card "Aset Bermasalah" & modalnya
                // langsung bisa didemokan (mirip contoh di desain: "UPS OFF" & "SOLAR HABIS").
                $forceProblem = $isToday && $shift['name'] === 'Siang';

                $this->insertDetail($checklistId, $assets, $forceProblem);

                $this->db->table('activity_log')->insert([
                    'checklist_id' => $checklistId,
                    'nama_petugas' => $namaPetugas,
                    'aktivitas'    => 'melakukan checklist shift ' . strtolower($shift['name']) . ' Data Center 1',
                    'created_at'   => $tanggal . ' ' . $jamPengecekan,
                ]);
            }
        }

        echo "Dummy data berhasil dibuat: {$jumlahHari} hari x 3 shift (kecuali shift malam hari ini).\n";
    }

    /**
     * Insert checklist_detail untuk semua aset pada 1 checklist.
     * $forceProblem = true -> paksa 1 UPS jadi Off dan 1 Solar Genset jadi 0%.
     */
    private function insertDetail(int $checklistId, array $assets, bool $forceProblem): void
    {
        $rows = [];
        $problemUpsAssigned   = false;
        $problemSolarAssigned = false;

        foreach ($assets as $asset) {
            $kondisi = $this->randomKondisi($asset['input_type']);

            if ($forceProblem && !$problemUpsAssigned && $asset['name'] === 'UPS A Data Center') {
                $kondisi = 'Off';
                $problemUpsAssigned = true;
            }

            if ($forceProblem && !$problemSolarAssigned && $asset['name'] === 'Solar Genset PLN') {
                $kondisi = '0';
                $problemSolarAssigned = true;
            }

            $rows[] = [
                'checklist_id'  => $checklistId,
                'asset_id'      => $asset['id'],
                'kondisi'       => $kondisi,
                'is_bermasalah' => AssetModel::isBermasalah($asset['input_type'], $kondisi) ? 1 : 0,
                'created_at'    => date('Y-m-d H:i:s'),
            ];
        }

        $this->db->table('checklist_detail')->insertBatch($rows);
    }

    /** Random kondisi realistis (mayoritas Normal/On, sesekali Standby/Off untuk variasi grafik tren). */
    private function randomKondisi(string $inputType): string
    {
        $r = mt_rand(1, 100);

        return match ($inputType) {
            'status_2' => $r <= 95 ? 'Normal' : 'Tidak Normal',
            'percentage' => match (true) {
                $r <= 55 => '100',
                $r <= 80 => '75',
                $r <= 95 => '25',
                default  => '0',
            },
            default => match (true) { // status_3
                $r <= 85 => 'Normal',
                $r <= 95 => 'Standby',
                default  => 'Off',
            },
        };
    }

    /** Tambahkan variasi menit di sekitar jam_mulai shift, mis. 07:00 -> 07:00..07:20. */
    private function jamSekitar(string $jamMulai): string
    {
        $ts = strtotime($jamMulai) + mt_rand(0, 20 * 60);
        return date('H:i:s', $ts);
    }
}
