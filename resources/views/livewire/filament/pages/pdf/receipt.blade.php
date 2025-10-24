@php
    $iconPath = public_path('favicon.ico');
    $icon = file_exists($iconPath) ? base64_encode(file_get_contents($iconPath)) : '';
@endphp

@include('partials.head')



<body class="text-[11px] font-sans leading-relaxed text-black p-4 ">

    {{-- <div
        class="absolute inset-0 bg-no-repeat bg-center bg-contain opacity-20 pointer-events-none"
        style="background-image: url('{{ asset('storage/favicon.ico') }}'); z-index: 0;">
    </div> --}}


    <div class="max-w-full mx-auto">
        <div class="flex items-center gap-2 mb-4">
            @if ($icon)
                <img src="data:image/x-icon;base64,{{ $icon }}" alt="Drive & Go" class="h-15 w-15 rounded-full" />
            @else
                <img src="{{ asset('favicon.ico') }}" alt="Drive & Go" class="h-15 w- rounded-full" />
            @endif

            <div class="text-center w-full">
                <h2 class="text-xl font-bold text-red-600">
                    Twayne <span class="text-gray-800">Garage Car Rental</span>
                </h2>
                <p class="text-sm">Car Rental Agreement Form</p>
                <p class="text-xs font-semibold text-gray-700 mt-1">
                    Agreement No: <span
                        class="text-black">{{ $payment->transaction_reference ?? '________________' }}</span>
                </p>
            </div>

            <p class="font-bold">Date:</p>
            <p>
                {{ $rental->created_at ? \Carbon\Carbon::parse($rental->created_at)->format('M d, Y') : now()->format('M d, Y') }}
            </p>
        </div>

        {{-- Renter Info --}}
        <div class="mb-4">
            <table class="w-full border-collapse">
                <tr class="border-b">

                    <td class="font-bold pr-2">Renter’s Name:</td>
                    <td class="pr-4">{{ $rental->user->name ?? '________________' }}</td>
                    <td class="font-bold pr-2">Age:</td>
                    <td class="pr-4">{{ $rental->user->age ?? '_____' }}</td>
                    <td class="font-bold pr-2">Contact No:</td>
                    <td>{{ $rental->user->phone_number ?? '________________' }}</td>
                </tr>
                <tr class="border-b">
                    <td class="font-bold pr-2">Address:</td>
                    <td colspan="5">{{ $rental->user->address ?? '______________________________________' }}</td>
                </tr>
                <tr class="border-b">
                    <td class="font-bold pr-2">Nationality:</td>
                    <td>{{ $rental->user->nationality ?? '_________' }}</td>
                    <td class="font-bold pr-2">ID Type:</td>
                    <td>{{ ucwords(str_replace('_', ' ', $rental->user->id_type ?? '')) ?: '_________' }}</td>

                </tr>
            </table>
        </div>

        {{-- Co-maker --}}
        <div class="mb-4">
            <p class="font-bold mb-1">Co-maker:</p>
            <table class="w-full border-collapse">
                <tr class="border-b">
                    <td class="font-bold pr-2">Name:</td>
                    <td class="pr-4">________________</td>
                    <td class="font-bold pr-2">Age:</td>
                    <td class="pr-4">_____</td>
                    <td class="font-bold pr-2">Contact No:</td>
                    <td>________________</td>
                </tr>
                <tr class="border-b">
                    <td class="font-bold pr-2">Address:</td>
                    <td colspan="5">______________________________________
                    </td>
                </tr>
            </table>
        </div>

        {{-- Car Details --}}
        <div class="mb-4">
            <p class="font-bold mb-1">Car Details:</p>
            <table class="w-full border-collapse">
                <tr class="border-b">
                    <td class="font-bold pr-2">Unit Make:</td>
                    <td class="pr-4">{{ $vehicle->manufacturer->brand ?? '_________' }}</td>
                    <td class="font-bold pr-2">Model:</td>
                    <td class="pr-4">{{ $vehicle->model ?? '_________' }}</td>
                    <td class="font-bold pr-2">Year:</td>
                    <td>{{ $vehicle->year ?? '_____' }}</td>
                </tr>
                <tr>
                    <td class="font-bold pr-2">Color:</td>
                    <td class="pr-4">{{ $vehicle->color ?? '_________' }}</td>
                    <td class="font-bold pr-2">Plate No:</td>
                    <td class="pr-4">{{ $vehicle->plate_number ?? '_________' }}</td>
                    <td class="font-bold pr-2">Transmission:</td>
                    <td>{{ $vehicle->transmission ?? 'AT/MT' }}</td>
                </tr>
            </table>
        </div>

        {{-- Rental Info --}}
        <div class="mb-4">
            <table class="w-full border-collapse">
                <tr class="border-b">
                    <td class="font-bold pr-2">Pickup Location:</td>
                    <td colspan="3">{{ $rental->pickup_location ?? '________________' }}</td>
                    <td class="font-bold pr-2">Drop Off Location:</td>
                    <td>{{ $rental->dropoff_location ?? '________________' }}</td>
                </tr>
                <tr>
                    <td class="font-bold pr-2">Return Date:</td>
                    <td class="pr-4">
                        {{ $rental->rental_end ? \Carbon\Carbon::parse($rental->rental_end)->format('M d, Y') : '_________' }}
                    </td>
                </tr>
            </table>
        </div>

        {{-- Trip Type --}}
        <div class="mb-6">
            <p class="font-bold mb-1">Trip Type:</p>
            <ul class="list-none pl-0 space-y-1">
                <li>{{ $rental->trip_type == 'pickup' ? '✔' : '___' }} Pick Up & Drop Off only</li>
                <li>{{ $rental->trip_type == 'hours' ? '✔' : '___' }} Hour/s</li>
                <li>{{ $rental->trip_type == 'roundtrip' ? '✔' : '___' }} Round trip only (10hrs max)</li>
                <li>{{ $rental->trip_type == '24hrs' ? '✔' : '___' }} 24 hours</li>
                <li>{{ $rental->trip_type == 'days' ? '✔' : '___' }} Days</li>
                <li>{{ $rental->trip_type == 'weeks' ? '✔' : '___' }} Week/weeks</li>
                <li>{{ $rental->trip_type == 'months' ? '✔' : '___' }} Month/months</li>
            </ul>
        </div>

        {{-- Signatures --}}
        <div class="flex justify-between mt-10 pt-6">
            <div class="w-1/2 text-center">
                <div class="border-t border-black w-4/5 mx-auto mb-1"></div>
                <p class="font-bold">Renter’s Signature</p>
                <p class="text-xs text-gray-600">Date: {{ now()->format('M d, Y') }}</p>
            </div>
            <div class="w-1/2 text-center">
                <div class="border-t border-black w-4/5 mx-auto mb-1"></div>
                <p class="font-bold">Co-maker’s Signature</p>
                <p class="text-xs text-gray-600">Date: _____________________</p>
            </div>
        </div>
    </div>
</body>
