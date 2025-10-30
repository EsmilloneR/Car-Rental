import L from "leaflet";
import "leaflet/dist/leaflet.css";
import "leaflet.markercluster";
import markerIcon from "leaflet/dist/images/marker-icon.png";
import markerShadow from "leaflet/dist/images/marker-shadow.png";

L.Icon.Default.mergeOptions({
    iconUrl: markerIcon,
    shadowUrl: markerShadow,
});

window.L = L;

import Swal from "sweetalert2";
window.Swal = Swal;

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

if (process.env.NODE_ENV === "production") {
    document.addEventListener("livewire:navigated", () => {
        document.addEventListener("contextmenu", (e) => e.preventDefault());

        document.onkeydown = function (e) {
            if (
                e.keyCode === 123 ||
                (e.ctrlKey && e.shiftKey && [73, 74, 67].includes(e.keyCode)) ||
                (e.ctrlKey && [85, 70].includes(e.keyCode))
            ) {
                e.preventDefault();
                return false;
            }
        };
    });
}

import "./echo";
