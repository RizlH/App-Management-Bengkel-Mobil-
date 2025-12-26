<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PembayaranModel;
use App\Models\PenerimaanServisModel;
use App\Models\PenjualanSparepartModel;
use App\Models\MekanikModel;
use App\Models\SparepartModel;
use App\Models\WorkOrderModel;

class Laporan extends BaseController
{
    protected $helpers = ['url', 'form'];
    
    public function index()
    {
        $tipeLaporan = $this->request->getGet('tipe') ?? 'pendapatan';
        $periode = $this->request->getGet('periode') ?? 'bulan_ini';
        
        $data = [
            'title' => 'Laporan',
            'tipe_laporan' => $tipeLaporan,
            'periode' => $periode
        ];

        // Set date range based on periode
        $dateRange = $this->getDateRange($periode);

        switch ($tipeLaporan) {
            case 'pendapatan':
                $data['laporan'] = $this->laporanPendapatan($dateRange);
                break;
            case 'mekanik':
                $data['laporan'] = $this->laporanPengerjaanMekanik($dateRange);
                break;
            case 'mobil_masuk':
                $data['laporan'] = $this->laporanMobilMasuk($dateRange);
                break;
            case 'stok':
                $data['laporan'] = $this->laporanStok();
                break;
        }

        return view('laporan/index', $data);
    }

    private function getDateRange($periode)
    {
        $today = date('Y-m-d');
        
        switch ($periode) {
            case 'hari_ini':
                return ['start' => $today, 'end' => $today];
            case 'minggu_ini':
                return [
                    'start' => date('Y-m-d', strtotime('monday this week')),
                    'end' => date('Y-m-d', strtotime('sunday this week'))
                ];
            case 'bulan_ini':
                return [
                    'start' => date('Y-m-01'),
                    'end' => date('Y-m-t')
                ];
            case 'tahun_ini':
                return [
                    'start' => date('Y-01-01'),
                    'end' => date('Y-12-31')
                ];
            default:
                return ['start' => $today, 'end' => $today];
        }
    }

    /**
     * LAPORAN PENDAPATAN (Gabungan dari pembayaran + penjualan sparepart)
     */
    private function laporanPendapatan($dateRange)
    {
        $pembayaranModel = new PembayaranModel();
        $penjualanModel = new PenjualanSparepartModel();
        
        // Data dari Pembayaran (Servis)
        $dataPembayaran = $pembayaranModel
            ->select('pembayaran.*, penerimaan_servis.nomor_servis, pelanggan.nama as nama_pelanggan')
            ->join('penerimaan_servis', 'penerimaan_servis.id = pembayaran.penerimaan_servis_id')
            ->join('pelanggan', 'pelanggan.id = penerimaan_servis.pelanggan_id')
            ->where('DATE(pembayaran.tanggal_bayar) >=', $dateRange['start'])
            ->where('DATE(pembayaran.tanggal_bayar) <=', $dateRange['end'])
            ->where('pembayaran.status_bayar', 'Lunas')
            ->orderBy('pembayaran.tanggal_bayar', 'DESC')
            ->findAll();

        // Data dari Penjualan Sparepart
        $dataPenjualan = $penjualanModel
            ->where('DATE(tanggal_penjualan) >=', $dateRange['start'])
            ->where('DATE(tanggal_penjualan) <=', $dateRange['end'])
            ->orderBy('tanggal_penjualan', 'DESC')
            ->findAll();

        // Gabungkan data
        $allData = [];
        
        // Format data pembayaran
        foreach ($dataPembayaran as $item) {
            $allData[] = [
                'jenis' => 'servis',
                'nomor_transaksi' => $item['nomor_invoice'],
                'tanggal' => $item['tanggal_bayar'],
                'pelanggan' => $item['nama_pelanggan'],
                'total_jasa' => $item['total_jasa'],
                'total_sparepart' => $item['total_sparepart'],
                'total_biaya' => $item['total_biaya']
            ];
        }
        
        // Format data penjualan sparepart
        foreach ($dataPenjualan as $item) {
            $allData[] = [
                'jenis' => 'sparepart',
                'nomor_transaksi' => $item['nomor_penjualan'],
                'tanggal' => $item['tanggal_penjualan'],
                'pelanggan' => $item['nama_pembeli'],
                'total_jasa' => 0,
                'total_sparepart' => $item['total_penjualan'],
                'total_biaya' => $item['total_penjualan']
            ];
        }

        // Sort by tanggal
        usort($allData, function($a, $b) {
            return strtotime($b['tanggal']) - strtotime($a['tanggal']);
        });

        // Hitung totals
        $totalPendapatan = array_sum(array_column($allData, 'total_biaya'));
        $totalJasa = array_sum(array_column($allData, 'total_jasa'));
        $totalSparepart = array_sum(array_column($allData, 'total_sparepart'));

        return [
            'data' => $allData,
            'data_pembayaran' => $dataPembayaran,
            'data_penjualan' => $dataPenjualan,
            'total_pendapatan' => $totalPendapatan,
            'total_jasa' => $totalJasa,
            'total_sparepart' => $totalSparepart,
            'jumlah_transaksi' => count($allData),
            'jumlah_servis' => count($dataPembayaran),
            'jumlah_penjualan_sparepart' => count($dataPenjualan)
        ];
    }

    /**
     * LAPORAN PENGERJAAN MEKANIK
     */
    private function laporanPengerjaanMekanik($dateRange)
    {
        $workOrderModel = new WorkOrderModel();
        $mekanikModel = new MekanikModel();
        
        // Get all active mechanics
        $mekanik = $mekanikModel->where('status_aktif', 1)->findAll();
        
        $data = [];
        
        foreach ($mekanik as $m) {
            // Hitung work order yang ditangani mekanik ini dalam periode
            $workOrders = $workOrderModel
                ->select('COUNT(*) as total_mobil')
                ->join('penerimaan_servis', 'penerimaan_servis.id = work_order.penerimaan_servis_id')
                ->where('work_order.mekanik_id', $m['id'])
                ->where('DATE(penerimaan_servis.tanggal_masuk) >=', $dateRange['start'])
                ->where('DATE(penerimaan_servis.tanggal_masuk) <=', $dateRange['end'])
                ->first();
            
            $data[] = [
                'id_mekanik' => $m['id'],
                'nama_mekanik' => $m['nama'],
                'kontak' => $m['kontak'],
                'posisi' => $m['posisi'],
                'total_mobil_ditangani' => $workOrders['total_mobil'] ?? 0
            ];
        }
        
        // Sort by jumlah mobil descending
        usort($data, function($a, $b) {
            return $b['total_mobil_ditangani'] - $a['total_mobil_ditangani'];
        });

        return [
            'data' => $data,
            'total_mekanik' => count($mekanik),
            'total_mobil_ditangani' => array_sum(array_column($data, 'total_mobil_ditangani'))
        ];
    }

    /**
     * LAPORAN MOBIL MASUK
     */
    private function laporanMobilMasuk($dateRange)
    {
        $penerimaanModel = new PenerimaanServisModel();
        
        $data = $penerimaanModel
            ->select('penerimaan_servis.*, pelanggan.nama as nama_pelanggan, kendaraan.nomor_plat, kendaraan.merk, kendaraan.tipe')
            ->join('pelanggan', 'pelanggan.id = penerimaan_servis.pelanggan_id')
            ->join('kendaraan', 'kendaraan.id = penerimaan_servis.kendaraan_id')
            ->where('DATE(penerimaan_servis.tanggal_masuk) >=', $dateRange['start'])
            ->where('DATE(penerimaan_servis.tanggal_masuk) <=', $dateRange['end'])
            ->orderBy('penerimaan_servis.tanggal_masuk', 'DESC')
            ->findAll();

        // Group by status
        $statusCount = [
            'menunggu' => 0,
            'proses' => 0,
            'selesai' => 0,
        ];

        foreach ($data as $item) {
            $status = strtolower($item['status']);
            if (isset($statusCount[$status])) {
                $statusCount[$status]++;
            }
        }

        // Group by merk kendaraan
        $merkCount = [];
        foreach ($data as $item) {
            $merk = $item['merk'] ?? 'Unknown';
            if (!isset($merkCount[$merk])) {
                $merkCount[$merk] = 0;
            }
            $merkCount[$merk]++;
        }

        return [
            'data' => $data,
            'total_mobil_masuk' => count($data),
            'status_count' => $statusCount,
            'merk_count' => $merkCount
        ];
    }

    /**
     * LAPORAN STOK SPAREPART
     */
    private function laporanStok()
    {
        $sparepartModel = new SparepartModel();
        
        $data = $sparepartModel
            ->orderBy('kategori', 'ASC')
            ->orderBy('nama_barang', 'ASC')
            ->findAll();

        $totalNilaiStok = 0;
        $stokRendah = 0;
        
        foreach ($data as &$item) {
            $nilaiStok = $item['stok'] * $item['harga_beli'];
            $item['nilai_stok'] = $nilaiStok;
            $totalNilaiStok += $nilaiStok;
            
            if ($item['stok'] < 5) {
                $stokRendah++;
            }
        }

        return [
            'data' => $data,
            'total_nilai_stok' => $totalNilaiStok,
            'stok_rendah' => $stokRendah,
            'total_item' => count($data)
        ];
    }

    /**
     * EXPORT EXCEL
     */
    public function exportExcel()
    {
        $tipeLaporan = $this->request->getGet('tipe') ?? 'pendapatan';
        $periode = $this->request->getGet('periode') ?? 'bulan_ini';
        
        $dateRange = $this->getDateRange($periode);

        // Get data based on report type
        switch ($tipeLaporan) {
            case 'pendapatan':
                $laporan = $this->laporanPendapatan($dateRange);
                $viewName = 'laporan/export_excel_pendapatan';
                break;
            case 'mekanik':
                $laporan = $this->laporanPengerjaanMekanik($dateRange);
                $viewName = 'laporan/export_excel_mekanik';
                break;
            case 'mobil_masuk':
                $laporan = $this->laporanMobilMasuk($dateRange);
                $viewName = 'laporan/export_excel_mobil_masuk';
                break;
            case 'stok':
                $laporan = $this->laporanStok();
                $viewName = 'laporan/export_excel_stok';
                break;
            default:
                $laporan = $this->laporanPendapatan($dateRange);
                $viewName = 'laporan/export_excel_pendapatan';
        }

        $data = [
            'tipe_laporan' => $tipeLaporan,
            'periode' => $periode,
            'laporan' => $laporan,
            'date_range' => $dateRange
        ];

        // Set headers for Excel
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"Laporan_" . ucfirst($tipeLaporan) . "_" . date('Y-m-d') . ".xls\"");
        header("Cache-Control: max-age=0");
        header("Pragma: no-cache");
        header("Expires: 0");

        return view($viewName, $data);
    }

    /**
     * EXPORT PDF (menggunakan HTML untuk print)
     */
    public function exportPdf()
    {
        $tipeLaporan = $this->request->getGet('tipe') ?? 'pendapatan';
        $periode = $this->request->getGet('periode') ?? 'bulan_ini';
        
        $dateRange = $this->getDateRange($periode);

        // Get report data based on type
        switch ($tipeLaporan) {
            case 'pendapatan':
                $laporan = $this->laporanPendapatan($dateRange);
                $viewName = 'laporan/export_pdf_pendapatan';
                break;
            case 'mekanik':
                $laporan = $this->laporanPengerjaanMekanik($dateRange);
                $viewName = 'laporan/export_pdf_mekanik';
                break;
            case 'mobil_masuk':
                $laporan = $this->laporanMobilMasuk($dateRange);
                $viewName = 'laporan/export_pdf_mobil_masuk';
                break;
            case 'stok':
                $laporan = $this->laporanStok();
                $viewName = 'laporan/export_pdf_stok';
                break;
            default:
                $laporan = $this->laporanPendapatan($dateRange);
                $viewName = 'laporan/export_pdf_pendapatan';
        }

        $data = [
            'tipe_laporan' => $tipeLaporan,
            'periode' => $periode,
            'laporan' => $laporan,
            'date_range' => $dateRange
        ];

        return view($viewName, $data);
    }
}