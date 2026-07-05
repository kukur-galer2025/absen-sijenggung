<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        $userId = $request->get('user_id', 'all');

        $query = Attendance::with('user')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'desc');

        if ($userId !== 'all') {
            $query->where('user_id', $userId);
        }

        $attendances = $query->get();
        $employees = User::where('role', 'pegawai')->get();

        return view('admin.reports.index', compact('attendances', 'employees', 'month', 'year', 'userId'));
    }

    public function exportPdf(Request $request)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        $userId = $request->get('user_id', 'all');

        $query = Attendance::with('user')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'asc');

        if ($userId !== 'all') {
            $query->where('user_id', $userId);
        }

        $attendances = $query->get();
        $monthName = Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y');

        $employeeName = 'Semua Pegawai';
        if ($userId !== 'all') {
            $employee = User::find($userId);
            if ($employee) {
                $employeeName = $employee->name;
            }
        }

        $pdf = Pdf::loadView('admin.reports.pdf', compact('attendances', 'monthName', 'month', 'year', 'employeeName'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download("Laporan_Presensi_Desa_Sijenggung_{$monthName}.pdf");
    }
}
