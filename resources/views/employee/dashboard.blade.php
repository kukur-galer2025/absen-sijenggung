<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Pegawai') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-emerald-50 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-lg relative flex items-center" role="alert">
                    <svg class="w-5 h-5 mr-2 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-lg relative flex items-center" role="alert">
                    <svg class="w-5 h-5 mr-2 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Card Selamat Datang & Absensi -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border-l-4 border-emerald-500">
                <div class="p-6 text-gray-900 bg-gradient-to-r from-emerald-50 to-stone-50">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div>
                            <h3 class="text-2xl font-bold text-emerald-800 mb-1">Halo, {{ Auth::user()->name }}!</h3>
                            <p class="text-sm text-emerald-600 font-medium">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                <span id="live-clock" class="font-mono text-emerald-700 font-semibold text-base"></span> WIB
                            </p>
                        </div>
                        

                </div>
            </div>



            <!-- Riwayat Absensi -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h4 class="text-lg font-semibold mb-4 text-gray-800">📋 Riwayat Absensi Terakhir</h4>
                    
                    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-emerald-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider">Jam Masuk</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider">Foto Masuk</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider">Jam Pulang</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider">Foto Pulang</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider">Lokasi Masuk</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider">Lokasi Pulang</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($history as $record)
                                <tr class="hover:bg-stone-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $record->date->translatedFormat('d M Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $record->check_in ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($record->check_in_photo)
                                            <a href="{{ Storage::url($record->check_in_photo) }}" target="_blank">
                                                <img src="{{ Storage::url($record->check_in_photo) }}" class="h-10 w-10 object-cover rounded shadow-sm border border-gray-200">
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $record->check_out ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($record->check_out_photo)
                                            <a href="{{ Storage::url($record->check_out_photo) }}" target="_blank">
                                                <img src="{{ Storage::url($record->check_out_photo) }}" class="h-10 w-10 object-cover rounded shadow-sm border border-gray-200">
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ strtolower($record->status) == 'terlambat' ? 'bg-orange-100 text-orange-800' : 'bg-emerald-100 text-emerald-800' }}">
                                            {{ ucfirst($record->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($record->check_in_latitude && $record->check_in_longitude)
                                            <div class="flex flex-col">
                                                <span class="text-xs">{{ number_format($record->check_in_latitude, 5) }}, {{ number_format($record->check_in_longitude, 5) }}</span>
                                                <a href="https://www.openstreetmap.org/?mlat={{ $record->check_in_latitude }}&mlon={{ $record->check_in_longitude }}#map=17/{{ $record->check_in_latitude }}/{{ $record->check_in_longitude }}" 
                                                   target="_blank" 
                                                   class="text-emerald-600 hover:text-emerald-800 underline flex items-center mt-1 text-xs">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                                    Peta
                                                </a>
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($record->check_out_latitude && $record->check_out_longitude)
                                            <div class="flex flex-col">
                                                <span class="text-xs">{{ number_format($record->check_out_latitude, 5) }}, {{ number_format($record->check_out_longitude, 5) }}</span>
                                                <a href="https://www.openstreetmap.org/?mlat={{ $record->check_out_latitude }}&mlon={{ $record->check_out_longitude }}#map=17/{{ $record->check_out_latitude }}/{{ $record->check_out_longitude }}" 
                                                   target="_blank" 
                                                   class="text-emerald-600 hover:text-emerald-800 underline flex items-center mt-1 text-xs">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                                    Peta
                                                </a>
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                            Belum ada riwayat absensi.
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Live clock WIB
        function updateClock() {
            const now = new Date();
            const wib = new Date(now.toLocaleString('en-US', { timeZone: 'Asia/Jakarta' }));
            const h = String(wib.getHours()).padStart(2, '0');
            const m = String(wib.getMinutes()).padStart(2, '0');
            const s = String(wib.getSeconds()).padStart(2, '0');
            document.getElementById('live-clock').textContent = h + ':' + m + ':' + s;
        }
        setInterval(updateClock, 1000);
        updateClock();


    </script>
    @endpush
</x-app-layout>
