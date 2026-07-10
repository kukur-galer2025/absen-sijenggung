<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Setting;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // Koordinat Kantor Balai Desa Sijenggung, Banjarmangu, Banjarnegara
    const OFFICE_LAT = -7.292740;
    const OFFICE_LNG = 109.667997;
    const MAX_RADIUS_KM = 70; // Maksimal 70 km dari kantor desa

    /**
     * Hitung jarak antara dua titik koordinat menggunakan formula Haversine (dalam km)
     */
    private function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Radius bumi dalam km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Validasi apakah lokasi masih dalam radius yang diizinkan
     */
    private function validateLocation(Request $request)
    {
        if ($request->latitude && $request->longitude) {
            $distance = $this->haversineDistance(
                self::OFFICE_LAT, self::OFFICE_LNG,
                $request->latitude, $request->longitude
            );

            if ($distance > self::MAX_RADIUS_KM) {
                return round($distance, 1);
            }
        }

        return false;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        
        // Employee sees their own today's status
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', Carbon::today())
            ->first();
            
        $deadline = Setting::where('key', 'attendance_deadline')->value('value') ?? '08:00';
            
        return view('attendance.index', compact('todayAttendance', 'deadline'));
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'photo' => ['required', 'string', 'starts_with:data:image/', 'max:2000000'],
        ]);

        // Cek radius lokasi
        $overDistance = $this->validateLocation($request);
        if ($overDistance !== false) {
            return back()->with('error', "Anda berada di luar radius absensi ({$overDistance} km dari kantor desa). Maksimal {self::MAX_RADIUS_KM} km.");
        }

        $user = $request->user();
        $today = Carbon::today();

        // Check if already checked in
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if ($attendance) {
            return back()->with('error', 'Anda sudah melakukan absen masuk hari ini.');
        }

        $now = Carbon::now();
        $deadlineTime = Setting::where('key', 'attendance_deadline')->value('value') ?? '08:00';
        $deadlineFormatted = \Carbon\Carbon::createFromFormat('H:i', $deadlineTime)->format('H:i:s');

        $status = $now->format('H:i:s') > $deadlineFormatted ? 'terlambat' : 'hadir';

        $photoPath = null;
        if ($request->photo) {
            $imageParts = explode(";base64,", $request->photo);
            if (count($imageParts) == 2) {
                $imageBase64 = base64_decode($imageParts[1]);

                // Deep Image Verification
                $imageInfo = @getimagesizefromstring($imageBase64);
                if (!$imageInfo || !in_array($imageInfo[2], [IMAGETYPE_WEBP, IMAGETYPE_JPEG, IMAGETYPE_PNG])) {
                    return back()->with('error', 'Keamanan: Format foto tidak valid atau terdeteksi manipulasi file berbahaya!');
                }

                $imageName = $user->id . '_' . time() . '_in.webp';
                \Illuminate\Support\Facades\Storage::disk('public')->put('attendances/' . $imageName, $imageBase64);
                $photoPath = 'attendances/' . $imageName;
            }
        }

        Attendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'check_in' => $now->format('H:i:s'),
            'status' => $status,
            'check_in_latitude' => $request->latitude,
            'check_in_longitude' => $request->longitude,
            'check_in_photo' => $photoPath,
        ]);

        $statusMsg = $status == 'terlambat' ? ' (Anda Terlambat)' : ' (Tepat Waktu)';
        return back()->with('success', 'Berhasil absen masuk pada ' . $now->format('H:i:s') . ' WIB' . $statusMsg . '.');
    }

    public function checkOut(Request $request)
    {
        $request->validate([
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'photo' => ['required', 'string', 'starts_with:data:image/', 'max:2000000'],
        ]);

        // Cek radius lokasi
        $overDistance = $this->validateLocation($request);
        if ($overDistance !== false) {
            return back()->with('error', "Anda berada di luar radius absensi ({$overDistance} km dari kantor desa). Maksimal " . self::MAX_RADIUS_KM . " km.");
        }

        $user = $request->user();
        $today = Carbon::today();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            return back()->with('error', 'Anda belum absen masuk hari ini.');
        }

        if ($attendance->check_out) {
            return back()->with('error', 'Anda sudah melakukan absen pulang.');
        }

        $photoPath = null;
        if ($request->photo) {
            $imageParts = explode(";base64,", $request->photo);
            if (count($imageParts) == 2) {
                $imageBase64 = base64_decode($imageParts[1]);

                // Deep Image Verification
                $imageInfo = @getimagesizefromstring($imageBase64);
                if (!$imageInfo || !in_array($imageInfo[2], [IMAGETYPE_WEBP, IMAGETYPE_JPEG, IMAGETYPE_PNG])) {
                    return back()->with('error', 'Keamanan: Format foto tidak valid atau terdeteksi manipulasi file berbahaya!');
                }

                $imageName = $user->id . '_' . time() . '_out.webp';
                \Illuminate\Support\Facades\Storage::disk('public')->put('attendances/' . $imageName, $imageBase64);
                $photoPath = 'attendances/' . $imageName;
            }
        }

        $attendance->update([
            'check_out' => Carbon::now()->format('H:i:s'),
            'check_out_latitude' => $request->latitude,
            'check_out_longitude' => $request->longitude,
            'check_out_photo' => $photoPath,
        ]);

        return back()->with('success', 'Berhasil absen pulang pada ' . Carbon::now()->format('H:i:s') . ' WIB.');
    }

    /**
     * Reset presensi hari ini (pegawai bisa ulang jika salah)
     */
    public function reset(Request $request)
    {
        $user = $request->user();
        $today = Carbon::today();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            return back()->with('error', 'Tidak ada data presensi hari ini untuk direset.');
        }

        $attendance->delete();

        return back()->with('success', 'Presensi hari ini berhasil direset. Silakan lakukan presensi ulang.');
    }
}
