<x-filament::page>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <div class="space-y-10 ">

        <div class="bg-white dark:bg-gray-900 shadow rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                🚗 Top 5 Most Rented Vehicles
            </h2>

            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase">
                                Vehicle</th>
                            <th
                                class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase">
                                Total Rentals</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($this->topVehicles as $vehicle)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <td class="px-4 py-3 text-gray-800 dark:text-gray-200 font-medium">
                                    {{ $vehicle->manufacturer->brand }} {{ $vehicle->model }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $vehicle->rentals_count }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2"
                                    class="px-4 py-3 text-center text-gray-500 dark:text-gray-400 italic">No data
                                    available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 shadow rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                🧑‍🤝‍🧑 Top 5 Loyal Customers
            </h2>

            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase">
                                Customer</th>
                            <th
                                class="px-4 py-3 text-left text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase">
                                Total Rentals</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($this->loyalCustomers as $customer)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <td class="px-4 py-3 text-gray-800 dark:text-gray-200 font-medium">{{ $customer->name }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $customer->total_rentals }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2"
                                    class="px-4 py-3 text-center text-gray-500 dark:text-gray-400 italic">No data
                                    available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament::page>
