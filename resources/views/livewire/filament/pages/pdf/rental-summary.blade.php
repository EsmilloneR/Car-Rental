@include('partials.head')

<body class="p-6 font-sans">
    <h1 class="text-2xl font-bold text-center mb-6">📊 Car Rental Summary Report</h1>

    {{-- Top Rented Vehicles --}}
    <h2 class="text-xl font-semibold mb-3">Top 5 Rented Vehicles</h2>
    <table class="min-w-full border border-gray-300 mb-6">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2 text-left">Vehicle</th>
                <th class="border px-4 py-2 text-left">Rentals</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($topVehicles as $vehicle)
                <tr class="border-b">
                    <td class="border px-4 py-2">{{ $vehicle->manufacturer->brand ?? 'N/A' }}
                        {{ $vehicle->model ?? 'N/A' }}</td>
                    <td class="border px-4 py-2">{{ $vehicle->rentals_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Loyal Customers --}}
    <h2 class="text-xl font-semibold mb-3">Top 5 Loyal Customers</h2>
    <table class="min-w-full border border-gray-300">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2 text-left">Customer</th>
                <th class="border px-4 py-2 text-left">Total Rentals</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($loyalCustomers as $customer)
                <tr class="border-b">
                    <td class="border px-4 py-2">{{ $customer->name }}</td>
                    <td class="border px-4 py-2">{{ $customer->rentals_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="text-sm text-gray-600 mt-8 text-right">
        Generated on {{ now()->format('F d, Y h:i A') }}
    </p>
</body>
