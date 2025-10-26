<x-filament::page>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <div class="space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <x-filament::card>
                <div class="text-sm text-gray-500">Total Rentals</div>
                <div class="text-2xl font-bold">{{ $this->totalRentals }}</div>
            </x-filament::card>

            <x-filament::card>
                <div class="text-sm text-gray-500">Total Income</div>
                <div class="text-2xl font-bold">₱ {{ number_format($this->totalIncome, 2) }}</div>
            </x-filament::card>

            <x-filament::card>
                <div class="text-sm text-gray-500">Date Range</div>
                <div class="text-sm">{{ $this->from }} → {{ $this->to }}</div>
            </x-filament::card>
        </div>

        <div class="flex gap-2 items-center">
            <x-filament::input.wrapper>
                <x-filament::input type="date" wire:model="from" />
            </x-filament::input.wrapper>
            <x-filament::input.wrapper>
                <x-filament::input type="date" wire:model="to" />
            </x-filament::input.wrapper>

            <x-filament::button wire:click="refreshStats" color="primary">
                Refresh
            </x-filament::button>

            <x-filament::button wire:click="exportPdf" color="success">
                Export PDF
            </x-filament::button>
        </div>

        <x-filament::card>
            <h3 class="text-lg font-semibold mb-2">Rentals per Vehicle</h3>
            <table class="w-full text-left text-sm">
                <thead class="text-gray-500 border-b">
                    <tr>
                        <th class="py-1">Vehicle</th>
                        <th class="py-1">Rentals</th>
                        <th class="py-1">Income</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->rentalsPerVehicle as $v)
                        <tr class="border-b">
                            <td class="py-1">{{ $v->vehicle->manufacturer->brand }} {{ $v->vehicle->model }}</td>
                            <td class="py-1">{{ $v->rentals }}</td>
                            <td class="py-1">₱ {{ number_format($v->income, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-2 text-center text-gray-500">No data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-filament::card>

        <x-filament::card>
            <h3 class="text-lg font-semibold mb-2">Income by Day</h3>
            <table class="w-full text-left text-sm">
                <thead class="text-gray-500 border-b">
                    <tr>
                        <th class="py-1">Date</th>
                        <th class="py-1">Income</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->incomeByDay as $day)
                        <tr class="border-b">
                            <td class="py-1">{{ $day->day }}</td>
                            <td class="py-1">₱ {{ number_format($day->income, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="py-2 text-center text-gray-500">No data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-filament::card>
    </div>
</x-filament::page>
