<?php

use Livewire\Volt\Component;
use App\Models\Vehicle;

new class extends Component {
    public Vehicle $vehicle;

    public function mount($id)
    {
        $this->vehicle = Vehicle::with(['manufacturer'])->findOrFail($id);
    }
};
?>

<div class="max-w-7xl mx-auto px-6 py-12">
    <section class="overflow-hidden font-poppins">
        <div class="max-w-6xl mx-auto lg:py-10">
            <div class="flex flex-wrap -mx-4">
                <div class="w-full mb-8 md:w-1/2 md:mb-0 px-4" x-data="{
                    mainImage: '{{ url('storage', $vehicle->photos[0] ?? 'vehicle/default-car.jpg') }}'
                }">
                    <div class="sticky top-0">
                        <div class="relative mb-6 rounded-xl overflow-hidden shadow-md">
                            <img x-bind:src="mainImage"
                                alt="{{ $vehicle->manufacturer->brand }} {{ $vehicle->model }}"
                                class="object-cover w-full h-[420px] rounded-lg">
                        </div>

                        <div class="flex flex-wrap gap-2">
                            @forelse ($vehicle->photos as $image)
                                <div class="w-20 h-20 cursor-pointer border-2 border-transparent hover:border-red-500 rounded-md overflow-hidden"
                                    x-on:click="mainImage='{{ url('storage', $image) }}'">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Thumbnail"
                                        class="object-cover w-full h-full">
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">No additional images.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-1/2 px-4">
                    <div class="lg:pl-10">
                        <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-4">
                            <span class="text-red-600">{{ $vehicle->manufacturer->brand }}</span>
                            {{ $vehicle->model }}
                            <span class="text-sm text-gray-500 dark:text-gray-400">({{ $vehicle->year }})</span>
                        </h2>

                        <div class="mb-6 text-gray-700 dark:text-gray-300">
                            <p class="font-semibold text-lg mb-2">
                                Seats: <span
                                    class="font-bold text-gray-900 dark:text-gray-100">{{ $vehicle->seats }}</span>
                            </p>
                            <p class="text-xl font-semibold">
                                From:
                                <span class="text-green-600 dark:text-green-400">
                                    {{ Number::currency($vehicle->rate_hour, 'PHP') }}
                                </span>
                                –
                                <span class="text-green-600 dark:text-green-400">
                                    {{ Number::currency($vehicle->rate_day, 'PHP') }}
                                </span>
                            </p>
                        </div>

                        <div
                            class="max-w-md max-h-100 overflow-y-auto pr-2 text-gray-700 dark:text-gray-400
                                space-y-3 [&>ul]:list-disc [&>ul]:pl-6 [&>p]:leading-relaxed">
                            {!! Str::markdown($vehicle->description) !!}
                        </div>

                        <div class="mt-8 flex flex-wrap items-center gap-4">
                            @if (!$vehicle->rentals->isNotEmpty())
                                <a href="/vehicles/{{ $vehicle->id }}/payment"
                                    class="w-full lg:w-2/5 p-4 bg-red-600 rounded-lg text-center text-white font-semibold hover:bg-red-700 transition-all duration-200"
                                    wire:navigate.hover>
                                    Rent Now
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
