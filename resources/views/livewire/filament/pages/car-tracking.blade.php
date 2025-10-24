<x-filament::page>
    <div id="map" class="w-full h-[600px] rounded-xl shadow"></div>

    {{-- Load your compiled JS that includes Leaflet + Echo --}}
    @vite(['resources/js/app.js', 'resources/css/app.css'])

    <script data-navigate-once>
        let map;

        function initMap() {
            const mapElement = document.getElementById('map');
            if (!mapElement || mapElement.dataset.loaded) return;
            mapElement.dataset.loaded = true;

            // Initialize map
            map = L.map('map').setView([8.215, 126.316], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© Drive & Go - Twayne Garage',
            }).addTo(map);

            console.log('Listening for GPS updates...');

            let markers = {};


            window.updateMarker = function(vehicleId, lat, lng, speed = 'Unknown') {
                if (!markers[vehicleId]) {
                    markers[vehicleId] = L.marker([lat, lng]).addTo(map);
                    markers[vehicleId].bindPopup(`🚗 Vehicle ${vehicleId}<br>Speed: ${speed} km/h`).openPopup();
                } else {
                    markers[vehicleId].setLatLng([lat, lng]);
                    markers[vehicleId].openPopup();
                }

                if (map.getZoom() < 15) {
                    map.setView([lat, lng], 15);
                }
            };

            window.Echo.channel('gps-tracker')
                .listen('.gps.updated', (event) => {
                    console.log("📡 New GPS update:", event.location);

                    if (event?.location?.latitude && event?.location?.longitude) {
                        updateMarker(
                            event.location.vehicle_id,
                            event.location.latitude,
                            event.location.longitude,
                            event.location.speed,
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
