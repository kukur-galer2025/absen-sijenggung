<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Presensi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Filter & Export -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border-l-4 border-emerald-500">
                <div class="p-6 bg-gradient-to-r from-emerald-50 to-stone-50">
                    <h3 class="text-lg font-bold text-emerald-800 mb-4">📊 Filter Laporan</h3>
                    <form method="GET" action="{{ route('reports.index') }}" class="flex flex-col md:flex-row flex-wrap md:items-end gap-4">
                        <div class="w-full md:w-auto">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                            <select name="month" class="w-full md:w-auto border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                        {{ Carbon\Carbon::createFromDate(null, $m, 1)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="w-full md:w-auto">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                            <select name="year" class="w-full md:w-auto border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                @for($y = 2024; $y <= date('Y') + 1; $y++)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="w-full md:w-auto">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pegawai</label>
                            <select name="user_id" class="w-full md:w-auto border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                <option value="all" {{ $userId === 'all' ? 'selected' : '' }}>Semua Pegawai</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ $userId == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col md:flex-row w-full md:w-auto gap-2">
                            <button type="submit" class="w-full md:w-auto px-4 py-2 bg-emerald-600 text-white font-semibold rounded-md shadow hover:bg-emerald-700 transition-colors justify-center text-center">
                                🔍 Tampilkan
                            </button>
                            <a href="{{ route('reports.exportPdf', ['month' => $month, 'year' => $year, 'user_id' => $userId]) }}" 
                               class="w-full md:w-auto px-4 py-2 bg-red-600 text-white font-semibold rounded-md shadow hover:bg-red-700 transition-colors inline-flex justify-center items-center">
                                📄 Export PDF
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel Laporan -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">
                        📋 Data Presensi — {{ Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }}
                    </h4>

                    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-emerald-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider">Nama Pegawai</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider">NIP</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider">Jam Masuk</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider">Jam Pulang</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-emerald-700 uppercase tracking-wider">Lokasi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($attendances as $i => $record)
                                <tr class="hover:bg-stone-50 transition-colors">
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $i + 1 }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 font-medium">{{ $record->date->translatedFormat('d M Y') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $record->user->name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $record->user->nip ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $record->check_in ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $record->check_out ?? '-' }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ strtolower($record->status) == 'terlambat' ? 'bg-orange-100 text-orange-800' : 'bg-emerald-100 text-emerald-800' }}">
                                            {{ ucfirst($record->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        @if($record->latitude && $record->longitude)
                                            <a href="https://www.openstreetmap.org/?mlat={{ $record->latitude }}&mlon={{ $record->longitude }}#map=17/{{ $record->latitude }}/{{ $record->longitude }}" 
                                               target="_blank" class="text-emerald-600 hover:text-emerald-800 underline text-xs">
                                                📍 Peta
                                            </a>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-sm text-gray-500 text-center">
                                        Belum ada data presensi untuk bulan ini.
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
</x-app-layout>
