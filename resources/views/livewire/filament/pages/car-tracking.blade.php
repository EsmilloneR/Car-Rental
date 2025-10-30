<x-filament::page>
    <div id="map" class="w-full h-[600px] rounded-xl shadow"></div>

    @vite(['resources/js/app.js', 'resources/css/app.css'])

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

            console.log('📡 Listening for GPS updates...');
            let markers = {};
            let activeVehicleId = null;

            window.updateMarker = function(vehicleId, lat, lng, speed = 'Unknown', vehicleName = 'Unknown', brand =
                'Unknown') {
                const popupContent = `
                🚗 <b>${brand} ${vehicleName}</b><br>
                Latitude: ${lat}<br>
                Longitude: ${lng}<br>
                Speed: ${speed} km/h
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
                            event.location.vehicle_name,
                            event.location.manufacturer_brand
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
