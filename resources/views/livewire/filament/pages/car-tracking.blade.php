<x-filament::page>
    <div
        class="relative bg-gradient-to-br from-gray-50 via-gray-100 to-gray-200 dark:from-gray-900 dark:via-gray-800 dark:to-gray-700 min-h-[700px] rounded-2xl p-4 md:p-6 shadow-xl border border-gray-200 dark:border-gray-700 transition-all duration-500 ease-in-out">

        <!-- Page Title -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <x-heroicon-o-map-pin class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                GPS Vehicle Tracking
            </h2>
            <span
                class="px-3 py-1 text-xs font-medium text-blue-700 bg-blue-100 dark:bg-blue-900 dark:text-blue-300 rounded-full shadow-sm">
                Live Monitoring Active
            </span>
        </div>

        <!-- Leaflet Map Container -->
        <div id="map"
            class="w-full h-[600px] rounded-xl ring-1 ring-gray-300 dark:ring-gray-700 shadow-inner overflow-hidden z-10 relative">
        </div>

        <!-- Status overlay -->
        <div
            class="absolute bottom-6 left-6 bg-white/90 dark:bg-gray-800/90 backdrop-blur-md px-4 py-2 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 text-sm text-gray-700 dark:text-gray-200 z-50">
            <p class="font-medium">📡 Listening for GPS updates...</p>
        </div>
    </div>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @filamentStyles
    @filamentScripts

    <script data-navigate-once>
        let map;

        function initMap() {
            const mapElement = document.getElementById('map');
            if (!mapElement || mapElement.dataset.loaded) return;
            mapElement.dataset.loaded = true;

            map = L.map('map').setView([8.215, 126.316], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© Drive & Go - Twayne Garage',
            }).addTo(map);

            map.attributionControl.setPrefix(false);

            console.log('📡 Listening for GPS updates...');
            let markers = {};
            let activeVehicleId = null;

            window.updateMarker = function(vehicleId, lat, lng, speed = 'Unknown', vehicleName = 'Unknown', brand =
                'Unknown') {
                const popupContent = `
                <b>
                Vehicle: ${brand} ${vehicleName}<br>
                Latitude: ${lat}<br>
                Longitude: ${lng}<br>
                Speed: ${speed} km/h
                </b>
            `;

                if (!markers[vehicleId]) {
                    markers[vehicleId] = L.marker([lat, lng]).addTo(map);
                    markers[vehicleId].bindPopup(popupContent).openPopup();
                } else {
                    markers[vehicleId].setLatLng([lat, lng]);
                    markers[vehicleId].bindPopup(popupContent);
                    markers[vehicleId].openPopup();
                }

                if (map.getZoom() < 15 || activeVehicleId === vehicleId) {
                    map.setView([lat, lng], 15);
                }

                activeVehicleId = vehicleId;
            };

            window.Echo.channel('gps-tracker')
                .listen('.gps.updated', (event) => {
                    console.log("📍 New GPS update:", event.location);

                    if (event?.location?.latitude && event?.location?.longitude) {
                        updateMarker(
                            event.location.vehicle_id,
                            event.location.latitude,
                            event.location.longitude,
                            event.location.speed,
                            event.location.manufacturer_brand,
                            event.location.vehicle_model
                        );
                    } else {
                        console.warn("⚠️ Missing coordinates in GPS event:", event);
                        alert("⚠️ GPS data for this vehicle is incomplete or missing.");
                    }
                });
        }

        window.addEventListener('load', () => setTimeout(initMap, 500));
        document.addEventListener('livewire:navigated', () => setTimeout(initMap, 500));
    </script>
</x-filament::page>
