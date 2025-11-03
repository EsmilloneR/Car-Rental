<?php

use Livewire\Volt\Component;
use App\Models\Rental;
use Carbon\Carbon;
use Livewire\WithPagination;
use App\Jobs\DeletedRental;

new class extends Component {
    use WithPagination;

    public $showModal = false;
    public $selectedRental = null;

    public $rentals;
    protected $listeners = ['refreshRentals' => 'loadRentals'];
    public function mount()
    {
        $this->loadRentals();
    }

    public function showDetails($id)
    {
        $this->selectedRental = Rental::with('vehicle.manufacturer')
            ->where('user_id', auth()->id())
            ->find($id);

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedRental = null;
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
                            <span class="text-red-500">{{ $vehicle->manufacturer->brand }}</span> {{ $vehicle->model }}
                            ({{ $vehicle->year }})
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
                        <p class="text-sm text-gray-700 dark:text-gray-300" x-data="{
                            status: '{{ $rental->status }}',
                            endTime: new Date('{{ \Carbon\Carbon::parse($rental->rental_end)->format('Y-m-d H:i:s') }}').getTime(),
                            remaining: '',
                            update() {
                                if (this.status !== 'ongoing') {
                                    this.remaining = '—';
                                    return;
                                }

                                const diff = this.endTime - Date.now();
                                if (diff <= 0) {
                                    this.remaining = 'Expired';
                                    return;
                                }

                                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                                const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
                                const minutes = Math.floor((diff / (1000 * 60)) % 60);
                                const seconds = Math.floor((diff / 1000) % 60);

                                this.remaining =
                                    (days > 0 ? days + 'd ' : '') +
                                    (hours > 0 ? hours + 'h ' : '') +
                                    (minutes > 0 ? minutes + 'm ' : '') +
                                    seconds + 's';
                            }
                        }"
                            x-init="update();
                            setInterval(() => update(), 1000)">
                            Time left: <span x-text="remaining" class="font-semibold text-red-500"></span>
                        </p>


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

                        <button wire:click="showDetails({{ $rental->id }})"
                            class="hover:text-green-700 text-sm font-medium transition cursor-pointer">
                            <span class="text-green-600">View Details</span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($showModal && $selectedRental)

            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/20 backdrop-blur-sm transition-opacity duration-300"
                x-transition.opacity>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-lg mx-4 overflow-hidden">
                    <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 p-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                            {{ $selectedRental->vehicle->manufacturer->brand }} {{ $selectedRental->vehicle->model }}
                        </h3>
                        <button wire:click="closeModal"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">&times;</button>
                    </div>

                    <div class="p-5 space-y-3 text-gray-700 dark:text-gray-300">
                        <img src="{{ asset('storage/' . $selectedRental->vehicle->photos[1]) }}"
                            alt="{{ $selectedRental->vehicle->model }}" class="w-full h-48 object-cover rounded-lg">
                        <p><span class="font-medium">Agreement No:</span> {{ $selectedRental->agreement_no }}</p>
                        <p><span class="font-medium">Vehicle Year:</span> {{ $selectedRental->vehicle->year }}</p>
                        <p><span class="font-medium">Plate Number:</span>
                            {{ strtoupper($selectedRental->vehicle->plate_number) }}</p>
                        <p><span class="font-medium">Color:</span> {{ ucfirst($selectedRental->vehicle->color) }}</p>
                        <p><span class="font-medium">Start Date:</span>
                            {{ \Carbon\Carbon::parse($selectedRental->rental_start)->format('M d, Y h:i A') }}</p>
                        <p><span class="font-medium">End Date:</span>
                            {{ \Carbon\Carbon::parse($selectedRental->rental_end)->format('M d, Y h:i A') }}</p>
                        <p><span class="font-medium">Total Amount:</span>
                            ₱{{ number_format($selectedRental->total, 2) }}</p>
                        <p><span class="font-medium">Status:</span>
                            <span class="@class([
                                'px-2 py-1 rounded-full text-xs font-semibold',
                                'bg-yellow-100 text-yellow-700' => $selectedRental->status === 'pending',
                                'bg-blue-100 text-blue-700' => $selectedRental->status === 'ongoing',
                                'bg-green-100 text-green-700' => $selectedRental->status === 'completed',
                                'bg-red-100 text-red-700' => $selectedRental->status === 'cancelled',
                            ])">
                                {{ ucfirst($selectedRental->status) }}
                            </span>
                        </p>
                    </div>

                    <div class="flex justify-end gap-3 border-t border-gray-200 dark:border-gray-700 p-4">
                        @if ($selectedRental->status === 'pending' && $selectedRental->paymongo_url)
                            <button wire:click="attemptPayment({{ $selectedRental->id }})"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                                Continue Payment
                            </button>
                        @endif

                        @if ($selectedRental->status === 'pending')
                            <button wire:click="attemptCancel({{ $selectedRental->id }})"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                                Cancel Booking
                            </button>
                        @endif

                        {{-- ✅ View Receipt button for completed rentals --}}
                        {{-- @if ($selectedRental->status === 'completed')
                            <a href="{{ route('payments.receipt', $selectedRental->id) }}" target="_blank"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                                View Receipt
                            </a>
                        @endif --}}

                        <button wire:click="closeModal"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200">
                            Close
                        </button>
                    </div>

                </div>
            </div>
        @endif

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
            window.open(url, '_blank');
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
            setTimeout(() => Livewire.dispatch('refreshRentals'), 2000);
        });
    });
</script>
