<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PelangganModel;
use App\Models\KendaraanModel;
use App\Models\MekanikModel;
use App\Models\PenerimaanServisModel;
use App\Models\PembayaranModel;
use App\Models\PenjualanSparepartModel;
use App\Models\SparepartModel;
use App\Models\WorkOrderModel;

class Dashboard extends BaseController
{
    public function index()
    {   
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        // Inisialisasi model
        $pelangganModel = new PelangganModel();
        $kendaraanModel = new KendaraanModel();
        $mekanikModel = new MekanikModel();
        $penerimaanModel = new PenerimaanServisModel();
        $pembayaranModel = new PembayaranModel();
        $penjualanModel = new PenjualanSparepartModel();
        $sparepartModel = new SparepartModel();
        $workOrderModel = new WorkOrderModel();

        // Hitung statistik dasar
        $total_pelanggan = $pelangganModel->countAll();
        $total_kendaraan = $kendaraanModel->countAll();
        $total_mekanik = $mekanikModel->where('status_aktif', 1)->countAll();
        $servis_aktif = $penerimaanModel->whereIn('status', ['Menunggu', 'Proses'])->countAll();

        // ============================================
        // LAPORAN PENDAPATAN BULAN INI (Servis + Sparepart)
        // ============================================
        
        // 1. Pendapatan dari Pembayaran (Servis) - Bulan ini
        $pendapatan_servis = $pembayaranModel
            ->where('MONTH(tanggal_bayar)', date('m'))
            ->where('YEAR(tanggal_bayar)', date('Y'))
            ->where('status_bayar', 'Lunas')
            ->selectSum('total_biaya')
            ->first()['total_biaya'] ?? 0;
        
        // Detail pendapatan servis
        $detail_pendapatan_servis = $pembayaranModel
            ->select('SUM(total_jasa) as total_jasa, SUM(total_sparepart) as total_sparepart_servis')
            ->where('MONTH(tanggal_bayar)', date('m'))
            ->where('YEAR(tanggal_bayar)', date('Y'))
            ->where('status_bayar', 'Lunas')
            ->first();

        // 2. Pendapatan dari Penjualan Sparepart - Bulan ini
        $pendapatan_sparepart = $penjualanModel
            ->where('MONTH(tanggal_penjualan)', date('m'))
            ->where('YEAR(tanggal_penjualan)', date('Y'))
            ->selectSum('total_penjualan')
            ->first()['total_penjualan'] ?? 0;

        // Total pendapatan bulan ini
        $pendapatan_bulan_ini = $pendapatan_servis + $pendapatan_sparepart;

        // ============================================
        // PENYELESAIAN SERVIS DARI WORK ORDER
        // ============================================
        
        // Hitung work order yang statusnya 'Selesai'
        $work_order_selesai = $workOrderModel
            ->where('status', 'Selesai')
            ->where('MONTH(tanggal_mulai)', date('m'))
            ->where('YEAR(tanggal_mulai)', date('Y'))
            ->countAllResults();

        // Hitung work order yang sedang berjalan (Proses) - DIGANTI MENJADI "Dikerjakan"
        $work_order_proses = $workOrderModel
            ->where('status', 'Dikerjakan')
            ->where('MONTH(tanggal_mulai)', date('m'))
            ->where('YEAR(tanggal_mulai)', date('Y'))
            ->countAllResults();

        // HITUNG BARU: Work Order Pending (status 'Pending' atau 'Menunggu')
        $work_order_pending = $workOrderModel
            ->whereIn('status', ['Pending', 'Menunggu'])
            ->where('MONTH(tanggal_mulai)', date('m'))
            ->where('YEAR(tanggal_mulai)', date('Y'))
            ->countAllResults();

        // Hitung rata-rata waktu penyelesaian dari work order yang selesai
        $rata_waktu_selesai = $workOrderModel
            ->select('AVG(DATEDIFF(tanggal_selesai, tanggal_mulai)) as rata_hari')
            ->where('status', 'Selesai')
            ->where('MONTH(tanggal_mulai)', date('m'))
            ->where('YEAR(tanggal_mulai)', date('Y'))
            ->where('tanggal_selesai IS NOT NULL')
            ->first();
        $rata_rata_waktu = $rata_waktu_selesai ? number_format($rata_waktu_selesai['rata_hari'] ?? 0, 1) : '0';

        // ============================================
        // STATISTIK LAINNYA
        // ============================================

        // Statistik servis
        $servis_menunggu = $penerimaanModel->where('status', 'Menunggu')->countAllResults();
        $servis_proses = $penerimaanModel->where('status', 'Proses')->countAllResults();
        $servis_selesai = $penerimaanModel->where('status', 'Selesai')->countAllResults();
        
        // Statistik sparepart
        $total_sparepart = $sparepartModel->countAll();
        $stok_rendah = $sparepartModel->where('stok <', 5)->countAllResults();

        // Mekanik terbaik (berdasarkan work order yang selesai)
        $top_mekanik = $mekanikModel
            ->select('mekanik.*, COUNT(work_order.id) as total_servis')
            ->join('work_order', 'work_order.mekanik_id = mekanik.id', 'left')
            ->where('mekanik.status_aktif', 1)
            ->where('work_order.status', 'Selesai')
            ->where('MONTH(work_order.tanggal_mulai)', date('m'))
            ->where('YEAR(work_order.tanggal_mulai)', date('Y'))
            ->groupBy('mekanik.id')
            ->orderBy('total_servis', 'DESC')
            ->first();

        // Pelanggan baru bulan ini
        $pelanggan_baru = $pelangganModel
            ->where('MONTH(created_at)', date('m'))
            ->where('YEAR(created_at)', date('Y'))
            ->countAllResults();

        // Penjualan sparepart bulan ini (jumlah transaksi)
        $penjualan_sparepart_count = $penjualanModel
            ->where('MONTH(tanggal_penjualan)', date('m'))
            ->where('YEAR(tanggal_penjualan)', date('Y'))
            ->countAllResults();

        // Work order bulan ini (total)
        $work_order_total = $workOrderModel
            ->where('MONTH(tanggal_mulai)', date('m'))
            ->where('YEAR(tanggal_mulai)', date('Y'))
            ->countAllResults();

        // Servis terbaru
        $servis_terbaru = $penerimaanModel
            ->select('penerimaan_servis.*, pelanggan.nama as nama_pelanggan, pelanggan.no_telepon as telepon_pelanggan, kendaraan.nomor_plat, kendaraan.merk, kendaraan.tipe')
            ->join('pelanggan', 'pelanggan.id = penerimaan_servis.pelanggan_id')
            ->join('kendaraan', 'kendaraan.id = penerimaan_servis.kendaraan_id')
            ->orderBy('penerimaan_servis.created_at', 'DESC')
            ->limit(8)
            ->find();

        // Data untuk dashboard
        $data = [
            'title' => 'Dashboard',
            'total_pelanggan' => $total_pelanggan,
            'total_kendaraan' => $total_kendaraan,
            'total_mekanik' => $total_mekanik,
            'servis_aktif' => $servis_aktif,
            
            // Pendapatan bulan ini (SESUAI LAPORAN)
            'pendapatan_bulan_ini' => $pendapatan_bulan_ini,
            'pendapatan_servis' => $pendapatan_servis,
            'pendapatan_sparepart' => $pendapatan_sparepart,
            'detail_pendapatan_servis' => [
                'total_jasa' => $detail_pendapatan_servis['total_jasa'] ?? 0,
                'total_sparepart_servis' => $detail_pendapatan_servis['total_sparepart_servis'] ?? 0
            ],
            
            // Penyelesaian dari Work Order - DIPERBARUI
            'work_order_selesai' => $work_order_selesai,
            'work_order_proses' => $work_order_proses, // Diganti menjadi "Dikerjakan" di view
            'work_order_pending' => $work_order_pending, // Data baru untuk "Pending"
            'rata_rata_waktu' => $rata_rata_waktu, // Bisa dihapus jika tidak digunakan lagi
            
            // Statistik lainnya
            'servis_menunggu' => $servis_menunggu,
            'servis_proses' => $servis_proses,
            'servis_selesai' => $servis_selesai,
            'total_sparepart' => $total_sparepart,
            'stok_rendah' => $stok_rendah,
            'top_mekanik' => $top_mekanik,
            'pelanggan_baru' => $pelanggan_baru,
            'penjualan_sparepart' => $penjualan_sparepart_count,
            'work_order' => $work_order_total,
            'servis_terbaru' => $servis_terbaru,
            'user' => [
                'name' => session()->get('full_name'),
                'email' => session()->get('email'),
                'last_login' => session()->get('last_login')
            ]
        ];

        return view('dashboard/index', $data);
    }
}