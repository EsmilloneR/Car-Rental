<div class="max-w-7xl mx-auto px-6 py-8">

    <div class="overflow-x-auto scrollbar-hide mb-8">
        <ul class="flex space-x-3">
            <li>
                <button wire:click="$set('selected_slug', [])"
                    class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium
                    {{ empty($selected_slug) ? 'bg-white text-black dark:bg-gray-100 dark:text-gray-900' : 'bg-gray-800 text-gray-300 hover:bg-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700' }}">
                    All
                </button>
            </li>

            @foreach ($slugs as $slug)
                <li wire:key="slug-{{ $slug->id }}">
                    <button wire:click="toggleBrand({{ $slug->id }})"
                        class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium transition-all
                        {{ in_array($slug->id, $selected_slug)
                            ? 'bg-white text-black dark:bg-gray-100 dark:text-gray-900'
                            : 'bg-gray-800 text-gray-300 hover:bg-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700' }}">
                        {{ $slug->brand }}
                    </button>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @forelse ($listCars as $cars)
            <div wire:key="car-{{ $cars->id }}"
                class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-300">
                <a href="/vehicles/{{ $cars->id }}/details" wire:navigate.hover>
                    <img src="{{ url('storage/', $cars->avatar) }}" alt="{{ $cars->manufacturer->brand }}"
                        class="object-cover w-full h-56">
                </a>

                <div class="p-4">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2">
                        {{ $cars->manufacturer->brand }} {{ $cars->model }}
                        <span class="text-sm text-gray-500 dark:text-gray-400">({{ $cars->year }})</span>
                    </h3>

                    <p class="text-gray-700 dark:text-gray-300">
                        <span class="font-medium text-gray-600 dark:text-gray-400">From:</span>
                        <span class="text-green-600 dark:text-green-400 font-semibold">
                            {{ Number::currency($cars->rate_hour, 'PHP') }}
                        </span>
                        —
                        <span class="text-green-600 dark:text-green-400 font-semibold">
                            {{ Number::currency($cars->rate_week, 'PHP') }}
                        </span>
                    </p>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 p-4 flex justify-center">
                    @if ($cars->rentals->isNotEmpty())
                        <span
                            class="inline-flex items-center gap-2 text-gray-500 dark:text-gray-400 font-medium cursor-not-allowed">
                            Reserved
                        </span>
                    @else
                        <a href="/vehicles/{{ $cars->id }}/payment"
                            class="inline-flex items-center gap-2 text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 font-medium transition-colors"
                            wire:navigate.hover>
                            <span>Rent Now</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-600 dark:text-gray-400 py-12">
                🚗 No cars available for these filters.
            </div>
        @endforelse
    </div>

    <div class="flex justify-center mt-10">
        {{ $listCars->links() }}
    </div>
</div>
@push('styles')
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endpush
