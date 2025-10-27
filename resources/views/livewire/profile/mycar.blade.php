<?php

use Livewire\Volt\Component;
use App\Models\Rental;
use Carbon\Carbon;
use Livewire\WithPagination;
use App\Jobs\DeletedRental;

new class extends Component {
    use WithPagination;

    public $rentals;
    protected $listeners = ['refreshRentals' => 'loadRentals'];
    public function mount()
    {
        $this->loadRentals();
    }

    public function loadRentals()
    {
        $this->rentals = Rental::with('vehicle.manufacturer')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
    }

    public function continueToPayment($id)
    {
        $rental = Rental::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$rental || !$rental->paymongo_url) {
            return;
        }

        // dd($rental->paymongo_url);
        $this->dispatch('redirect-to-payment', url: $rental->paymongo_url);
    }

    public function attemptPayment($id)
    {
        $this->dispatch('confirm-payment', id: $id);
    }

    public function attemptCancel($id)
    {
        $this->dispatch('confirm-cancel', id: $id);
    }

    public function cancelRental($id)
    {
        $rental = Rental::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$rental) {
            return;
        }

        $now = now();
        $start = \Carbon\Carbon::parse($rental->rental_start);
        $diffInMinutes = $now->diffInMinutes($start, false);

        if ($diffInMinutes > 24 * 60) {
            $rental->update(['status' => 'cancelled']);
            $this->dispatch('alert', type: 'success', message: 'Booking cancelled successfully.');
        } else {
            $rental->update(['status' => 'cancelled']);

            DeletedRental::dispatch($rental->id)->delay(now()->addMinute());
            $this->dispatch('alert', type: 'info', message: 'Booking cancelled and will be removed in 1 Minute.');
        }

        $this->loadRentals();
        $this->resetPage();

        $this->dispatch('schedule-refresh');
    }
};
?>


<div class="max-w-6xl mx-auto p-6 font-poppins">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-6">My Rentals</h2>

    @if ($rentals->isEmpty())
        <div class="text-center text-gray-600 dark:text-gray-400 py-10">
            🚗 No rentals found yet. Start booking your first car today!
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach ($rentals as $rental)
                @php $vehicle = $rental->vehicle; @endphp

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <img src="{{ asset('storage/' . $vehicle->avatar) }}" alt="{{ $vehicle->manufacturer->brand }}"
                        class="w-full h-48 object-cover">

                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-1">
                            {{ $vehicle->manufacturer->brand }} {{ $vehicle->model }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                            Agr No: <span class="font-medium">{{ $rental->agreement_no }}</span>
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                            Year: <span class="font-medium">{{ $vehicle->year }}</span>
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                            Plate: <span class="font-medium">{{ strtoupper($vehicle->plate_number) }}</span>
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                            Color: <span class="font-medium capitalize">{{ $vehicle->color }}</span>
                        </p>
                    </div>

                    <div class="px-4 pb-4 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            Start: <span
                                class="font-medium">{{ \Carbon\Carbon::parse($rental->rental_start)->format('M d, Y') }}</span>
                        </p>
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            End: <span
                                class="font-medium">{{ \Carbon\Carbon::parse($rental->rental_end)->format('M d, Y') }}</span>
                        </p>
                    </div>

                    <div
                        class="flex justify-between items-center bg-gray-50 dark:bg-gray-700 px-4 py-3 border-t dark:border-gray-600">
                        <span
                            class="px-3 py-1 text-xs font-semibold rounded-full
                            @if ($rental->status === 'pending') bg-yellow-100 text-yellow-700
                            @elseif($rental->status === 'ongoing') bg-blue-100 text-blue-700
                            @elseif($rental->status === 'completed') bg-green-100 text-green-700
                            @elseif($rental->status === 'cancelled') bg-red-100 text-red-700
                            @else bg-gray-200 text-gray-700 @endif">
                            {{ ucfirst($rental->status) }}
                        </span>


                        @if (in_array($rental->status, ['pending']))
                            @if ($rental->paymongo_url)
                                <button wire:click="attemptPayment({{ $rental->id }})"
                                    class="hover:text-green-700 text-sm font-medium transition cursor-pointer">
                                    <span class=" text-green-600"> Continue Payment</span>
                                </button>
                            @endif
                            <button wire:click="attemptCancel({{ $rental->id }})"
                                class=" hover:text-red-700 text-sm font-medium transition cursor-pointer">
                                <span class="text-red-600">Cancel</span>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('confirm-cancel', ({
            id
        }) => {
            Swal.fire({
                title: 'Cancel Booking?',
                text: "You can only cancel up to 24 hours before your booking starts.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, cancel it'
            }).then((result) => {
                if (result.isConfirmed) {
                    // ✅ Find the current Livewire component and call the PHP method
                    const component = Livewire.find(document.querySelector('[wire\\:id]')
                        .getAttribute('wire:id'));
                    component.call('cancelRental', id);
                }
            });
        });

        Livewire.on('confirm-payment', (id) => {
            Swal.fire({
                title: 'Continue to Payment?',
                text: "You will be redirected to complete your payment.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Continue'
            }).then((result) => {
                if (result.isConfirmed) {
                    const component = Livewire.find(document.querySelector('[wire\\:id]')
                        .getAttribute('wire:id'));
                    component.call('continueToPayment', id);
                }
            });
        });

        Livewire.on('redirect-to-payment', ({
            url
        }) => {
            console.log(url);
            if (url) {
                window.location.href = url;
            }
        });


        Livewire.on('alert', ({
            type,
            message
        }) => {
            Swal.fire({
                icon: type,
                title: message,
                timer: 2000,
                showConfirmButton: false
            });
        });


        Livewire.on('schedule-refresh', () => {
            // Wait a little longer than 1 minute (to let queue job finish)
            setTimeout(() => {
                Livewire.dispatch('refreshRentals');
            }, 65000); // 65 seconds
        });
    });
</script>
