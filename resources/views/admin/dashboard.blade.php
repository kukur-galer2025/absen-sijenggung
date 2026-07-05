<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border-l-4 border-emerald-500">
                <div class="p-6 text-gray-900 bg-gradient-to-r from-emerald-50 to-stone-50">
                    <h3 class="text-2xl font-bold text-emerald-800">Ringkasan Absensi Hari Ini</h3>
                    <p class="text-sm text-emerald-600 font-medium">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }} — <span id="live-clock" class="font-mono"></span> WIB</p>
                </div>
            </div>

            <!-- Ringkasan Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5 flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Total Pegawai</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalPegawai }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-emerald-100 p-5 flex items-center">
                    <div class="p-3 rounded-full bg-emerald-100 text-emerald-600 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-emerald-600 font-medium">Hadir (Tepat Waktu)</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $hadir }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-orange-100 p-5 flex items-center">
                    <div class="p-3 rounded-full bg-orange-100 text-orange-600 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-orange-600 font-medium">Terlambat</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $terlambat }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5 flex items-center">
                    <div class="p-3 rounded-full bg-gray-100 text-gray-500 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Belum Absen</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $belumAbsen }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-lg font-semibold text-gray-800">📋 Daftar Kehadiran</h4>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-emerald-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider">Nama Pegawai</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider">Jam Masuk</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider">Jam Pulang</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider">Lokasi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($attendances as $record)
                                <tr class="hover:bg-stone-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-800 font-bold border border-emerald-200">
                                                    {{ substr($record->user->name, 0, 1) }}
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $record->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $record->user->nip ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $record->check_in ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $record->check_out ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ strtolower($record->status) == 'terlambat' ? 'bg-orange-100 text-orange-800' : 'bg-emerald-100 text-emerald-800' }}">
                                            {{ ucfirst($record->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($record->latitude && $record->longitude)
                                            <a href="https://www.openstreetmap.org/?mlat={{ $record->latitude }}&mlon={{ $record->longitude }}#map=17/{{ $record->latitude }}/{{ $record->longitude }}" 
                                               target="_blank" 
                                               class="text-emerald-600 hover:text-emerald-800 underline flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                                Lihat Peta
                                            </a>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                            Belum ada pegawai yang absen hari ini.
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
    <script>
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
