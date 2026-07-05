<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Formulir Presensi') }}
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
                            <h3 class="text-2xl font-bold text-emerald-800 mb-1">Catat Kehadiran Anda</h3>
                            <p class="text-sm text-emerald-600 font-medium">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                <span id="live-clock" class="font-mono text-emerald-700 font-semibold text-base"></span> WIB
                            </p>
                        </div>
                        
                        <div class="mt-4 md:mt-0 flex flex-wrap gap-2">
                            <!-- Tombol Absen Masuk -->
                            <form id="form-check-in" action="{{ route('attendance.checkIn') }}" method="POST" class="w-full md:w-auto">
                                @csrf
                                <input type="hidden" name="latitude" id="checkin-lat">
                                <input type="hidden" name="longitude" id="checkin-lng">
                                <button type="submit" id="btn-check-in"
                                        class="w-full md:w-auto px-5 py-3 bg-emerald-600 text-white font-semibold rounded-lg shadow-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-opacity-75 transition-all transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                                        disabled>
                                    @if($todayAttendance)
                                        ✅ Sudah Masuk ({{ $todayAttendance->check_in }})
                                    @else
                                        📍 Absen Masuk
                                    @endif
                                </button>
                            </form>

                            <!-- Tombol Absen Pulang -->
                            <form id="form-check-out" action="{{ route('attendance.checkOut') }}" method="POST" class="w-full md:w-auto">
                                @csrf
                                <input type="hidden" name="latitude" id="checkout-lat">
                                <input type="hidden" name="longitude" id="checkout-lng">
                                <button type="submit" id="btn-check-out"
                                        class="w-full md:w-auto px-5 py-3 bg-amber-500 text-white font-semibold rounded-lg shadow-md hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-opacity-75 transition-all transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                                        disabled>
                                    @if($todayAttendance && $todayAttendance->check_out)
                                        ✅ Sudah Pulang ({{ $todayAttendance->check_out }})
                                    @else
                                        📍 Absen Pulang
                                    @endif
                                </button>
                            </form>

                            <!-- Tombol Reset Presensi -->
                            @if($todayAttendance)
                                <form id="form-reset" action="{{ route('attendance.reset') }}" method="POST" class="w-full md:w-auto">
                                    @csrf
                                    <button type="button" id="btn-reset"
                                            class="w-full md:w-auto px-5 py-3 bg-red-500 text-white font-semibold rounded-lg shadow-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-opacity-75 transition-all transform hover:scale-105">
                                        🔄 Reset Presensi
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- GPS Status -->
                    <div id="gps-status" class="mt-4 text-xs text-gray-500 flex items-start">
                        <svg class="animate-spin h-4 w-4 mr-2 text-emerald-500 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="flex-1 leading-relaxed">Mendeteksi lokasi GPS...</span>
                    </div>

                    <!-- Radius Info -->
                    <div class="mt-2 text-xs text-gray-400 flex items-start">
                        <svg class="w-3.5 h-3.5 mr-2 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                        <span class="flex-1 leading-relaxed">Radius presensi: maksimal 70 km dari Kantor Balai Desa Sijenggung</span>
                    </div>

                    <!-- Waktu Terlambat Info -->
                    <div class="mt-2 text-xs text-gray-400 flex items-start">
                        <svg class="w-3.5 h-3.5 mr-2 text-orange-400 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                        <span class="flex-1 leading-relaxed">Batas waktu presensi tepat waktu: <strong class="text-gray-500 whitespace-nowrap">{{ $deadline }} WIB</strong></span>
                    </div>
                </div>
            </div>

            <!-- Peta Lokasi Kantor Desa -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border-l-4 border-amber-400">
                <div class="p-4">
                    <h4 class="text-lg font-semibold mb-3 text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                        Lokasi Kantor Balai Desa Sijenggung
                    </h4>
                    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
                    <div class="rounded-lg overflow-hidden border border-gray-200 shadow-sm relative">
                        <div id="map" style="width: 100%; height: 350px; z-index: 1;"></div>
                        <button id="btn-locate" class="absolute top-3 right-3 z-[1000] bg-white p-2.5 rounded-full shadow-lg text-gray-700 hover:text-blue-600 focus:outline-none transition-transform transform hover:scale-110 border border-gray-100" title="Lokasi Saya">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" class="hidden"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" class="hidden"></path><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"></circle><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v2m0 16v2m10-10h-2M4 12H2"></path></svg>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">📍 Jl. Jenggawur - Banjarmangu, Sijonggung, Sijenggung, Kec. Banjarmangu, Kab. Banjarnegara, Jawa Tengah 53452</p>
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

        const hasCheckIn = {{ $todayAttendance ? 'true' : 'false' }};
        const hasCheckOut = {{ ($todayAttendance && $todayAttendance->check_out) ? 'true' : 'false' }};

        // Setup Leaflet Map
        const officeLat = -7.292740;
        const officeLng = 109.667997;
        const map = L.map('map').setView([officeLat, officeLng], 12);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        // Marker Kantor
        const officeMarker = L.marker([officeLat, officeLng]).addTo(map)
            .bindPopup('<b>Kantor Balai Desa Sijenggung</b><br>Pusat Presensi');
        
        // Lingkaran batas 70km
        L.circle([officeLat, officeLng], {
            color: '#10b981',
            fillColor: '#10b981',
            fillOpacity: 0.05,
            radius: 70000 // 70 km
        }).addTo(map);

        let userMarker = null;
        let userCircle = null;

        document.getElementById('btn-locate').addEventListener('click', function(e) {
            e.preventDefault();
            if (userMarker) {
                map.setView(userMarker.getLatLng(), 16);
            } else {
                map.locate({setView: true, maxZoom: 16});
            }
        });

        // GPS Geolocation
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    const accuracy = position.coords.accuracy;

                    // Gambar lokasi user di map
                    if (userMarker) map.removeLayer(userMarker);
                    if (userCircle) map.removeLayer(userCircle);

                    const userIcon = L.divIcon({
                        html: `<div style="background-color: #3b82f6; width: 16px; height: 16px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 5px rgba(0,0,0,0.5);"></div>`,
                        className: '',
                        iconSize: [16, 16],
                        iconAnchor: [8, 8]
                    });

                    userMarker = L.marker([lat, lng], {icon: userIcon}).addTo(map).bindPopup('Lokasi Anda saat ini');
                    userCircle = L.circle([lat, lng], {
                        radius: accuracy,
                        color: '#3b82f6',
                        fillColor: '#3b82f6',
                        fillOpacity: 0.2,
                        weight: 1
                    }).addTo(map);

                    // Zoom ke lokasi user
                    map.setView([lat, lng], 15);

                    // Fill hidden inputs
                    document.getElementById('checkin-lat').value = lat;
                    document.getElementById('checkin-lng').value = lng;
                    document.getElementById('checkout-lat').value = lat;
                    document.getElementById('checkout-lng').value = lng;

                    // Hitung jarak dari kantor desa (Haversine)
                    const R = 6371;
                    const dLat = (officeLat - lat) * Math.PI / 180;
                    const dLng = (officeLng - lng) * Math.PI / 180;
                    const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                              Math.cos(lat * Math.PI / 180) * Math.cos(officeLat * Math.PI / 180) *
                              Math.sin(dLng/2) * Math.sin(dLng/2);
                    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                    const distance = (R * c).toFixed(1);

                    const isInRadius = distance <= 70;
                    const statusColor = isInRadius ? 'text-emerald-700' : 'text-red-600';
                    const statusIcon = isInRadius 
                        ? '<svg class="w-4 h-4 mr-2 text-emerald-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>'
                        : '<svg class="w-4 h-4 mr-2 text-red-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>';
                    const statusMsg = isInRadius 
                        ? 'Dalam radius — ' + distance + ' km dari kantor desa'
                        : 'Di luar radius! — ' + distance + ' km dari kantor desa (maks 70 km)';

                    document.getElementById('gps-status').innerHTML = 
                        statusIcon +
                        '<div class="flex-1 leading-relaxed">' +
                        '<span class="' + statusColor + ' font-medium">' + statusMsg + '</span>' +
                        '<div class="text-gray-400 text-[10px] mt-0.5">(' + lat.toFixed(6) + ', ' + lng.toFixed(6) + ')</div>' +
                        '</div>';

                    // Aktifkan tombol jika lokasi sudah dikunci dan valid
                    if (!hasCheckIn) {
                        document.getElementById('btn-check-in').disabled = false;
                    }
                    if (hasCheckIn && !hasCheckOut) {
                        document.getElementById('btn-check-out').disabled = false;
                    }
                },
                function(error) {
                    document.getElementById('gps-status').innerHTML = 
                        '<svg class="w-4 h-4 mr-2 text-red-400 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>' +
                        '<span class="flex-1 text-red-600 leading-relaxed">Gagal mendeteksi lokasi. Harap aktifkan GPS/Location di browser Anda.</span>';
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        } else {
            document.getElementById('gps-status').innerHTML = 
                '<span class="text-red-600">Browser tidak mendukung GPS.</span>';
        }

        // SweetAlert2 Confirmation for Reset
        const btnReset = document.getElementById('btn-reset');
        if (btnReset) {
            btnReset.addEventListener('click', function(e) {
                Swal.fire({
                    title: 'Reset Presensi?',
                    text: 'Data absen masuk/pulang hari ini akan dihapus dan Anda harus melakukan presensi ulang.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#9ca3af',
                    confirmButtonText: 'Ya, Reset Data!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-xl',
                        confirmButton: 'rounded-lg',
                        cancelButton: 'rounded-lg'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-reset').submit();
                    }
                });
            });
        }
    </script>
    @endpush
</x-app-layout>
