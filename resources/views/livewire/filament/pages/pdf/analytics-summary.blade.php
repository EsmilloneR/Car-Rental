<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head')
</head>

<body class="p-6 text-gray-800">
    <h2 class="text-2xl font-bold mb-2">Rental Report ({{ $from }} → {{ $to }})</h2>
    <p>Total Rentals: <span class="font-semibold">{{ $totalRentals }}</span></p>
    <p>Total Income: <span class="font-semibold text-green-600">₱ {{ number_format($totalIncome, 2) }}</span></p>

    <h3 class="text-xl font-semibold mt-6 mb-2">Rentals per Vehicle</h3>
    <table class="min-w-full border border-gray-300 text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-3 py-2 text-left">Vehicle</th>
                <th class="border px-3 py-2 text-left">Rentals</th>
                <th class="border px-3 py-2 text-left">Income</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rentalsPerVehicle as $r)
                <tr class="odd:bg-white even:bg-gray-50">
                    <td class="border px-3 py-2">
                        {{ optional($r->vehicle)->model ?? 'Vehicle #' . $r->vehicle->manufacturer->brand }}</td>
                    <td class="border px-3 py-2">{{ $r->rentals }}</td>
                    <td class="border px-3 py-2">₱ {{ number_format($r->income, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3 class="text-xl font-semibold mt-8 mb-2">Income by Day</h3>
    <table class="min-w-full border border-gray-300 text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-3 py-2 text-left">Date</th>
                <th class="border px-3 py-2 text-left">Income</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($incomeByDay as $d)
                <tr class="odd:bg-white even:bg-gray-50">
                    <td class="border px-3 py-2">{{ $d->day }}</td>
                    <td class="border px-3 py-2">₱ {{ number_format($d->income, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
