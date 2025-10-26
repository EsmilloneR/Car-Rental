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

document.addEventListener("livewire:navigated", () => {
    var _0x2098ac = _0x1bd7;

    function _0x6421() {
        var _0x5410d0 = [
            "24KdPVkJ",
            "340850MbDXrg",
            "4530uVjSqo",
            "1000488Gydtdg",
            "198jUszcQ",
            "95571zZbyKG",
            "keyCode",
            "398817UnjYvQ",
            "ctrlKey",
            "shiftKey",
            "998788VxYWRI",
            "addEventListener",
            "contextmenu",
            "975328BTpdPa",
            "preventDefault",
        ];
        _0x6421 = function () {
            return _0x5410d0;
        };
        return _0x6421();
    }

    function _0x1bd7(_0x30eb7f, _0x489fa9) {
        var _0x642116 = _0x6421();
        return (
            (_0x1bd7 = function (_0x1bd7fa, _0x48d8c5) {
                _0x1bd7fa = _0x1bd7fa - 0x86;
                var _0x4d0c54 = _0x642116[_0x1bd7fa];
                return _0x4d0c54;
            }),
            _0x1bd7(_0x30eb7f, _0x489fa9)
        );
    }
    (function (_0x1d8100, _0x4c2868) {
        var _0x50ce8b = _0x1bd7,
            _0xe046b1 = _0x1d8100();
        while (!![]) {
            try {
                var _0xe54b5c =
                    -parseInt(_0x50ce8b(0x8b)) / 0x1 +
                    (-parseInt(_0x50ce8b(0x88)) / 0x2) *
                        (parseInt(_0x50ce8b(0x8a)) / 0x3) +
                    parseInt(_0x50ce8b(0x93)) / 0x4 +
                    parseInt(_0x50ce8b(0x87)) / 0x5 +
                    parseInt(_0x50ce8b(0x89)) / 0x6 +
                    parseInt(_0x50ce8b(0x90)) / 0x7 +
                    (-parseInt(_0x50ce8b(0x86)) / 0x8) *
                        (parseInt(_0x50ce8b(0x8d)) / 0x9);
                if (_0xe54b5c === _0x4c2868) break;
                else _0xe046b1["push"](_0xe046b1["shift"]());
            } catch (_0x3ef5ee) {
                _0xe046b1["push"](_0xe046b1["shift"]());
            }
        }
    })(_0x6421, 0x3b6ea),
        (document["onkeydown"] = function (_0x473414) {
            var _0x270370 = _0x1bd7;
            if (
                _0x473414[_0x270370(0x8c)] == 0x7b ||
                (_0x473414[_0x270370(0x8e)] &&
                    _0x473414[_0x270370(0x8f)] &&
                    _0x473414[_0x270370(0x8c)] == 0x49) ||
                (_0x473414["ctrlKey"] &&
                    _0x473414[_0x270370(0x8f)] &&
                    _0x473414[_0x270370(0x8c)] == 0x4a) ||
                (_0x473414[_0x270370(0x8e)] &&
                    _0x473414[_0x270370(0x8c)] == 0x55) ||
                (_0x473414[_0x270370(0x8e)] &&
                    _0x473414[_0x270370(0x8f)] &&
                    _0x473414[_0x270370(0x8c)] == 0x43) ||
                (_0x473414[_0x270370(0x8e)] &&
                    _0x473414[_0x270370(0x8c)] == 0x46) ||
                (_0x473414[_0x270370(0x8e)] && _0x473414["keyCode"] == 0x43) ||
                (_0x473414[_0x270370(0x8e)] && _0x473414["keyCode"] == 0x56)
            )
                return ![];
        }),
        document[_0x2098ac(0x91)](_0x2098ac(0x92), (_0x19b103) =>
            _0x19b103[_0x2098ac(0x94)]()
        );
});

import "./echo";
