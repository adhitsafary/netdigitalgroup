use Maatwebsite\Excel\Concerns\FromCollection;

class PerbaikanExport implements FromCollection
{
public function __construct($request)
{
$this->request = $request;
}

public function collection()
{
$query = Perbaikan::query();
// (Same filtering logic as controller)

return $query->get();
}
}