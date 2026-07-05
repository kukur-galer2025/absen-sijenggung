<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengaturan Sistem') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="mb-4 bg-emerald-50 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-lg relative flex items-center" role="alert">
                    <svg class="w-5 h-5 mr-2 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span class="block sm:inline text-sm sm:text-base">{{ session('success') }}</span>
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Batas Waktu Presensi') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Atur batas jam untuk menentukan status kehadiran pegawai (Hadir / Terlambat).') }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('settings.update') }}" class="mt-6 space-y-6">
                            @csrf
                            
                            <div>
                                <x-input-label for="attendance_deadline" :value="__('Batas Waktu Tepat Waktu (WIB)')" />
                                <x-text-input id="attendance_deadline" name="attendance_deadline" type="time" class="mt-1 block w-full sm:w-1/2" :value="old('attendance_deadline', $attendanceDeadline)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('attendance_deadline')" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Simpan Pengaturan') }}</x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
