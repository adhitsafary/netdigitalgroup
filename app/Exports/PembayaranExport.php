namespace App\Exports;

use App\Models\BayarPelanggan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PembayaranExport implements FromCollection, WithHeadings
{
    protected $pembayaran;

    // Konstruktor untuk menerima data pembayaran
    public function __construct($pembayaran)
    {
        $this->pembayaran = $pembayaran;
    }

    // Method untuk mengambil data yang akan diekspor
    public function collection()
    {
        return $this->pembayaran;
    }

    // Method untuk menambahkan heading pada file Excel
    public function headings(): array
    {
        return [
            'ID Pelanggan',
            'Nama Pelanggan',
            'Alamat Pelanggan',
            'Nomor Telepon',
            'Metode Transaksi',
            'Jumlah Pembayaran',
            'Paket',
            'Tanggal Tagih',
            'Tanggal Pembayaran',
            'Status Pembayaran',
        ];
    }
}
