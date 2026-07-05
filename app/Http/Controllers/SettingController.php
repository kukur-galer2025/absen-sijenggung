<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        
        $attendanceDeadline = Setting::where('key', 'attendance_deadline')->value('value') ?? '08:00';
        
        return view('admin.settings.index', compact('attendanceDeadline'));
    }

    public function update(Request $request)
    {

        $request->validate([
            'attendance_deadline' => 'required|date_format:H:i',
        ]);

        Setting::updateOrCreate(
            ['key' => 'attendance_deadline'],
            ['value' => $request->attendance_deadline]
        );

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
