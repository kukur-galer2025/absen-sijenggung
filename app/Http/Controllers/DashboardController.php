<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            // Admin sees today's attendance summary
            $today = Carbon::today();
            $attendances = Attendance::with('user')
                ->whereDate('date', $today)
                ->get();
            
            $totalPegawai = \App\Models\User::where('role', 'pegawai')->count();
            $hadir = $attendances->where('status', 'hadir')->count();
            $terlambat = $attendances->where('status', 'terlambat')->count();
            $belumAbsen = $totalPegawai - ($hadir + $terlambat);
            
            return view('admin.dashboard', compact('attendances', 'totalPegawai', 'hadir', 'terlambat', 'belumAbsen'));
        }

        // Employee sees their own today's status
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', Carbon::today())
            ->first();

        // And maybe a brief history
        $history = Attendance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        return view('employee.dashboard', compact('todayAttendance', 'history'));
    }
}
