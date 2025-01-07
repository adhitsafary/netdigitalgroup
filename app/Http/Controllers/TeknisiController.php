<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Perbaikan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TeknisiController extends Controller
{
    public function index(Request $request)
    {
        $query = Perbaikan::query();

        // Filter berdasarkan tanggal
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Pencarian berdasarkan ID pelanggan atau nama pelanggan
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('id_plg', 'like', '%' . $request->search . '%')
                    ->orWhere('nama_plg', 'like', '%' . $request->search . '%');
            });
        }

        // Sorting berdasarkan tanggal pembuatan
        $sort = $request->get('sort', 'asc');
        $query->orderBy('created_at', $sort);

        // Ambil data perbaikan yang statusnya Proses
        $perbaikan = $query->where('status', 'Proses')->get();

        // Data untuk chart mingguan
        $weeklyData = Perbaikan::selectRaw('WEEK(created_at) as week, COUNT(*) as total')
            ->groupBy('week')
            ->pluck('total', 'week');

        // Data untuk chart bulanan
        $monthlyData = Perbaikan::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        // Data untuk chart tahunan
        $yearlyData = Perbaikan::selectRaw('YEAR(created_at) as year, COUNT(*) as total')
            ->groupBy('year')
            ->pluck('total', 'year');

        return view('teknisi.index', compact('perbaikan', 'sort', 'weeklyData', 'monthlyData', 'yearlyData'));
    }
    function coba()
    {
        return view('coba');
    }


    public function rekapTeknisi(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfMonth();

        $rekap = Perbaikan::selectRaw('teknisi, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('teknisi')
            ->get();

        $totalPerbaikan = $rekap->sum('total');

        return view('teknisi.rekap_teknisi', compact('rekap', 'totalPerbaikan', 'startDate', 'endDate'));
    }
}
