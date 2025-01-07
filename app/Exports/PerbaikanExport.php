namespace App\Exports;

use App\Models\Perbaikan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class PerbaikanExport implements FromQuery
{
use Exportable;

protected $request;

public function __construct($request)
{
$this->request = $request;
}

public function query()
{
$query = Perbaikan::query();

// Apply filters based on request
if ($this->request->has('date')) {
$query->whereDate('tanggal', $this->request->date);
}

// Tambahkan lebih banyak filter sesuai kebutuhan
return $query;
}
}
