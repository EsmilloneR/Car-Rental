<x-filament-panels::page>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @push('scripts')
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js'></script>
        {{-- <script>
            document.addEventListener('livewire:navigated', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',

                    height: 'auto',
                    events: @json($rentals),
                    eventTimeFormat: {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    },
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    eventDisplay: 'block',
                });
                calendar.render();
            });
        </script> --}}


        <script>
            document.addEventListener('livewire:navigated', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    height: 'auto',
                    events: @json($rentals),
                    eventTimeFormat: {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    },
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    eventDisplay: 'block',

                    eventClick: function(info) {
                        info.jsEvent.preventDefault();
                        if (info.event.url) {
                            window.open(info.event.url, "_self");
                        }
                    },
                });
                calendar.render();
            });
        </script>
    @endpush

    <div id='calendar'></div>

</x-filament-panels::page>
